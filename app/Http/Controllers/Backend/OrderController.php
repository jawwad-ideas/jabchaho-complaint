<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\OrderItemImage;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use App\Jobs\UserCreated as UserCreated;

use App\Http\Requests\Backend\OrderSaveRequest;
use App\Models\Order;
use App\Jobs\SendEmailOnOrderCompletion as SendEmailOnOrderCompletion;
use App\Console\commands\SyncLaundryData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
#use App\Models\OrdersImages;
class OrderController extends Controller
{

    public function index(Request $request)
    {
        $order_number       = $request->input('order_number');
        $customer_email     = $request->input('customer_email');
        $customer_name      = $request->input('customer_name');
        $telephone          = $request->input('telephone');
        $status             = $request->segment(3);

        //$orders = Order::select('*')->orderBy('id', 'desc');
        $orders = Order::withCount([
            'images', // Total images
            'before', // Count with image_type = 1
            'after', // Count with image_type = 2
        ])
        ->orderBy('id', 'desc');

        if (!empty($order_number)) {
            $orders->where('orders.order_id', '=',  $order_number );
        }

        if (!empty($customer_email)) {
            $orders->where('orders.customer_email', 'like',  '%'.$customer_email.'%' );
        }

        if (!empty($customer_name)) {
            $orders->where('orders.customer_name', 'like',  '%'.$customer_name.'%' );
        }

        if (!empty($telephone)) {
            $orders->where('orders.telephone', '=',  '%'.$telephone.'%' );
        }

        if (!empty($status)) {
            $orders->where('orders.status', '=',  $status );
        }

        $orders = $orders->latest()->paginate( config('constants.per_page') );
        //dd($orders);
        $filterData = [
            'order_number' => $order_number,
            'order_status' => $status,
            'customer_email' => $customer_email,
            'customer_name' => $customer_name,
            'telephone' => $telephone
        ];

        return view('backend.orders.index', compact('orders'))->with($filterData);
    }

    public function completeOrder( Request $request )
    {
        $orderId = $request->input('orderId');
        try {
            $order     = new Order();
            $order->where('id',$orderId )->first()->update( [ 'updated_at'=>now() , 'status' => 2 ]);

            // Dispatch job to send emails
            dispatch(new SendEmailOnOrderCompletion($orderId));
            $this->queueWorker();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function syncOrder( Request $request )
    {
        try {
            $syncObject = new SyncLaundryData();
            $syncObject->manualSync();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($orderId)
    {
        $order = Order::with(['orderItems.images' => function ($query) {
            $query->where('status', 1);
        }])->find($orderId);
        return view('backend.orders.edit', [
            'order' => $order
        ]);
    }

    public function delete( Request $request )
    {
        $imageId = $request->input('imageId');
        $orderNumber = $request->input('orderNumber');
        try {
            //$orderImagesModel     = new OrderItemImage();
            $orderImagesModel= OrderItemImage::where('id',$imageId)->first();

            /*$uploadFolderPath   = config('constants.files.orders').'/'.$orderNumber;
            $newPath           = $uploadFolderPath."delete";
            $newPath           = public_path($newPath."/".$orderImagesModel->imagename );

            if ($orderImagesModel->image_type == "Before Wash") {
                $currentPath           = $uploadFolderPath."before";
                $currentPath           = public_path($currentPath.$orderImagesModel->imagename);
            }else if ( $orderImagesModel->image_type == "After Wash") {
                $currentPath           = $uploadFolderPath."after";
                $currentPath           = public_path($currentPath."/".$orderImagesModel->imagename);
            }
            // Move the file
            if (File::move( $currentPath, $newPath)) {
                $orderImagesModel->update(['updated_at'=>now(),'status'=>0]);
                return response()->json(['success' => true]);
            }*/
            $orderImagesModel->update(['updated_at'=>now(),'status'=>0]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function save( Request $request )
    {
        if ( $request->has('order_id') ) {
            $orderImages        =  [];
            $adminUser          = $request->user()->id;
            $orderId            = $request->get('order_id');
            $remarks            = $request->get('remarks');
            $orderNumber        = $request->get('order_number');
            $uploadFolderPath   = config('constants.files.orders').'/'.$orderNumber;
            $filePath           = public_path($uploadFolderPath);

            $orderUpdateArray =  [ 'updated_at'=>now(), 'remarks' => $remarks ];
            $attachmentName = null;

            if( $request->has('remarks_attachment') ){
                $attachment = $request->file('remarks_attachment');
                $attachmentName    =   $orderNumber.'-'.time().'-'.uniqid(rand(), true).'.' . $attachment->getClientOriginalExtension();
                $attachment->move( $filePath, $attachmentName );
                $orderUpdateArray["attachments"]  = $attachmentName;
            }

            $order = Order::where(['id' =>$orderId ])->first();
            $isToken = Arr::get($order, 'token');
            if( is_null($isToken) ){
                $token = sha1(uniqid(mt_rand(), true));
                $orderUpdateArray["token"]  = $token;
            }

            $order->update(
                $orderUpdateArray
            );

            if( $request->has('image') ) {
                foreach ($request->file('image') as $itemId => $imageTypes) {
                    foreach ($imageTypes as $type => $files) {
                        if ($type == "pickup_images") {
                            $imageType = "Before Wash";
                            $tempfilePath           = $uploadFolderPath."/before";
                            $tempfilePath           = public_path($tempfilePath);
                        }else if ($type == "delivery_images") {
                            $imageType = "After Wash";
                            $tempfilePath           = $uploadFolderPath."/after";
                            $tempfilePath           = public_path($tempfilePath);
                        }

                        foreach ($files as $file) {
                            // Save file and process it
                            $image = $file;
                            $newName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $image->getClientOriginalExtension();
                            $image->move( $tempfilePath , $newName);

                            $orderImages[] = [
                                'item_id' => $itemId,
                                'image_type' => $imageType, // 'pickup_images' or 'delivery_images'
                                'imagename' => $newName,
                                'admin_user' => $adminUser,
                                'status' => 1,
                            ];
                        }
                    }
                }

                if (!empty($orderImages)) {
                    $ordersImagesModel = new OrderItemImage;
                    $ordersImagesModel->createOrderItemImage($orderImages);

                }
            }
            return redirect()->route('orders.edit', ['order_id' => $orderId])
                ->with('success', 'Order created successfully.');
        }

        return view('backend.orders.index');
    }


    public function downloadImages($orderId=0,$folderName='')
    {
        //public\assets\uploads\orders\101621
        $directoryPath = public_path("assets/uploads/orders/{$orderId}/{$folderName}");

        // Check if the directory exists
        if (!File::exists($directoryPath)) {
            return response()->json(['error' => 'Directory does not exist.'], 404);
        }

        // Get all files in the directory
        $files = File::files($directoryPath);

        if (empty($files)) {
            return response()->json(['error' => 'No files found in the directory.'], 404);
        }

        // Create a ZIP file
        $zipFileName = "{$folderName}_images.zip";
        $zipFilePath = storage_path("app/{$zipFileName}");

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create ZIP file.'], 500);
        }

        // Download the ZIP file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
