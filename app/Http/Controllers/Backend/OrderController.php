<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\OrderItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Jobs\SendEmailOnOrderCompletion as SendEmailOnOrderCompletion;
use App\Console\commands\SyncLaundryData;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Illuminate\Support\Arr;
use App\Http\Requests\Backend\OrderSaveRequest;
use Intervention\Image\Facades\Image;
#use App\Models\OrdersImages;
class OrderController extends Controller
{

    public function uploadSave( Request $request ){
        dd($request->all());
    }

    public function uploadView( Request $request ){
        return view('backend.orders.temp');
    }

    public function itemImage(  Request $request  ){

        $orderItemImage = OrderItemImage::with('orderItem.order')->orderBy('id', 'desc');

        $filterData = [
            'barcode'               => $request->get('barcode', ''),
            'service_type' => $request->get('service_type', ''),
            'item_name' => $request->get('item_name', ''),
            'order_number' => $request->get('order_number', ''),
            'customer_email' => $request->get('customer_email', ''),
            'is_issue_identify_options' => [1 => "No", 2 => "Yes"],
            'customer_name' => $request->get('customer_name', ''),
            'telephone' => $request->get('telephone', ''),
            'issue' =>  $request->get('issue', ''),
            'location_type' =>  $request->get('location_type', ''),
        ];

        // Apply filters based on request
        if ($filterData['barcode']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('barcode', 'like', '%' . $filterData['barcode'] . '%');
            });
        }

        if ($filterData['issue']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('is_issue_identify', '=',  $filterData['issue'] );
            });
        }

        if ($filterData['service_type']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('service_type', 'like', '%' . $filterData['service_type'] . '%');
            });
        }

        if ($filterData['item_name']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('item_name', 'like', '%' . $filterData['item_name'] . '%');
            });
        }

        if ($filterData['order_number']) {
            $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                $query->where('order_id', 'like', '%' . $filterData['order_number'] . '%');
            });
        }

        if ($filterData['customer_email']) {
            $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                $query->where('customer_email', 'like', '%' . $filterData['customer_email'] . '%');
            });
        }

        if ($filterData['customer_name']) {
            $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                $query->where('customer_name', 'like', '%' . $filterData['customer_name'] . '%');
            });
        }

        if ($filterData['telephone']) {
            $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                $query->where('telephone', 'like', '%' . $filterData['telephone'] . '%');
            });
        }

        if (!empty($filterData['location_type'])) {
            
            if($filterData['location_type'] == strtolower(config('constants.laundry_location_type.store')))
            {
                $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                    $query->whereNotNull('orders.location_type');
                });
            } 
            else
            {
                $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                    $query->whereNull('orders.location_type');
                });
            }
        }

        $orderItemImage = $orderItemImage->latest()->paginate( config('constants.per_page') );

        return view('backend.orders.item', compact('orderItemImage'))->with($filterData);

    }
    public function index(Request $request)
    {
        $order_number       = $request->input('order_number');
        $customer_email     = $request->input('customer_email');
        $customer_name      = $request->input('customer_name');
        $telephone          = $request->input('telephone');
        $before_email       = $request->input('before_email');
        $after_email        = $request->input('after_email');
        $status             = $request->segment(3);
        $location_type      = $request->input('location_type');

        //$orders = Order::select('*')->orderBy('id', 'desc');
        $orders = Order::withCount([
            'images', // Total images
            'before', // Count with image_type = 1
            'after', // Count with image_type = 2
            'orderItems as items_count', // Count of order items
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

        if (!empty($after_email)) {
            $orders->where('orders.final_email', '=',  $after_email );
        }

        if (!empty($before_email)) {
            $orders->where('orders.before_email', '=',  $before_email );
        }


        if (!empty($telephone)) {
            $orders->where('orders.telephone', '=',  '%'.$telephone.'%' );
        }

        if (!empty($status)) {
            $orders->where('orders.status', '=',  $status );
        }

        if (!empty($location_type)) {
            
            if($location_type == strtolower(config('constants.laundry_location_type.store')))
            {
                $orders->whereNotNull('orders.location_type');
            } 
            else
            {
                $orders->whereNull('orders.location_type');
            }
        }

        $orders = $orders->latest()->paginate( config('constants.per_page') );
        //dd($orders);
        $filterData = [
            'order_number' => $order_number,
            'order_status' => $status,
            'customer_email' => $customer_email,
            'customer_name' => $customer_name,
            'telephone' => $telephone,
            'email_status_options' => [ 1 => "No", 2 => "Yes"],
            'before_email' => $before_email ,
            'after_email' => $after_email,
            'location_type' =>$location_type
        ];

        return view('backend.orders.index', compact('orders'))->with($filterData);
    }

    public function completeOrder( Request $request )
    {
        $orderId = $request->input('orderId');
        try {
            $order     = new Order();
            $order->where('id',$orderId )->first()->update( [ 'updated_at'=>now() , 'status' => 2 ]);

            try {
                $adminUser      = $request->user()->id;
                $historyData = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => 'order_complete' ,
                    'admin_user'    => $adminUser,
                    'data'          => null
                ];

                $this->addHistory($historyData);
            }catch ( \Exception $exception ){
                die($exception->getMessage());
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendEmail( Request $request ){
        try {
            $orderId = $request->input('orderId');
            $emailType = $request->input('emailType');
            $data = null;
            if ($emailType == "before_email") {

                $remarks = $request->input('remarks');
                $itemsIssuesl = $request->input('itemsIssues');
                $orderUpdateArray["before_email"] = 2;
                $orderUpdateArray["before_email_remarks"] = $remarks;
                $orderUpdateArray["before_email_options"] = $itemsIssuesl;
                $data = json_encode ( [ 'before_email_remarks' => $remarks , 'before_email_options' => $itemsIssuesl ] );
            } else {
                $orderUpdateArray["final_email"] = 2;
            }

            $orderUpdateArray["updated_at"] = now();
            //Order Update
            $order = Order::where(['id' => $orderId])->first();
            $order->update(
                $orderUpdateArray
            );

            //email Queue Called.
            dispatch(new SendEmailOnOrderCompletion( $orderId, $emailType ));
            $this->queueWorker();

            try {
                $adminUser      = $request->user()->id;
                $historyData = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => $emailType ,
                    'admin_user'    => $adminUser,
                    'data'          => $data
                ];

                $this->addHistory($historyData);
            }catch ( \Exception $exception ){
                die($exception->getMessage());
            }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function syncOrder( Request $request )
    {
        try
        {
                // Call the command
                \Artisan::call('sync:laundry-orders');

                // Optionally, capture the command's output
                $output = \Artisan::output();

            try {
                $adminUser      = $request->user()->id;
                $historyData = [
                    'order_id'      => null,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => 'sync_orders',
                    'admin_user'    => $adminUser,
                    'data'          => null
                ];

                $this->addHistory($historyData);
            }catch ( \Exception $exception ){
                die($exception->getMessage());
            }


                // Return a response
                return response()->json([
                    'success' => true,
                    'message' => 'Command executed successfully!',
                    'output' => $output,
                ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($orderId)
    {
        $order = Order::with(['orderItems.images' => function ($query) {
            $query->where('status', 1);
        }])->find($orderId);


        $beforeEmailShow = $afterEmailShow = false;
        foreach($order->orderItems as $item):
            foreach ($item->images as $image):
                if( $afterEmailShow && $beforeEmailShow ):
                    break;
                endif;
                if( $image->image_type == "After Wash" ):
                    $afterEmailShow = true;
                    break;
                elseif( $image->image_type == "Before Wash" ):
                    $beforeEmailShow = true;
                endif;
            endforeach;
            if( $afterEmailShow && $beforeEmailShow ):
                break;
            endif;
        endforeach;

        return view('backend.orders.edit', [
            'order' => $order,
            'showCompleteButton'   => $afterEmailShow && $order->status != 2,
            'sendFinalEmail'       => $afterEmailShow,
            'sendFinalEmailTitle'       =>  (  $order->final_email == 2 ) ? 'Resend Email After Wash' : 'Send Email After Wash',
            'sendBeforeEmail' => $beforeEmailShow,
            'sendBeforeEmailTitle' => (  $order->before_email == 2 ) ? 'Resend Email Before Wash' : 'Send Email Before Wash'
        ]);

    }

    public function delete( Request $request )
    {
        $imageId = $request->input('imageId');
        $orderNumber = $request->input('orderNumber');
        try {
            $orderImagesModel= OrderItemImage::where('id',$imageId)->first();

            if( $orderImagesModel->image_type == "After Wash" ):
                $folderName = 'after';
            elseif ($orderImagesModel->image_type == "Before Wash" ):
                $folderName = 'before';
            endif;

            $directoryPath = public_path(config('constants.files.orders')."/{$orderNumber}/{$folderName}");
            $realImage = $directoryPath . '/' . $orderImagesModel->imagename ;
            $deleteDirectoryPath = public_path(config('constants.files.orders')."/{$orderNumber}/delete");

            if( !File::exists($deleteDirectoryPath) ){
                File::makeDirectory($deleteDirectoryPath,0777, true, true);
            }

            $deleteImage = $deleteDirectoryPath. '/' . $orderImagesModel->imagename;

            if ( File::move( $realImage, $deleteImage ) ) {
                if( File::exists($deleteImage) ){

                    try {
                        $adminUser      = $request->user()->id;
                        $itemId         = $orderImagesModel->item_id;
                        $orderItemModel = OrderItem::where('id', $itemId)->first();
                        $historyData = [
                            'order_id'      => $orderItemModel->order_id,
                            'item_id'       => $itemId,
                            'item_image_id' => $imageId,
                            'action'        => "delete_image",
                            'admin_user'    => $adminUser,
                            'data' => null
                        ];

                        $this->addHistory($historyData);
                    }catch ( \Exception $exception ){
                        die($exception->getMessage());
                    }

                    $orderImagesModel->update(['updated_at'=>now(),'status'=>0]);
                    return response()->json(['success' => true]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addHistory( $historyData = [] ){
        $ordersImagesModel = new OrderHistory();
        $ordersImagesModel->createOrderHistory($historyData);
    }

    public function save( OrderSaveRequest $request )
    {
        //dd( $request->all() );
        if ( $request->has('order_id') ) {
            $orderImages        = $historyData =  [];
            $adminUser          = $request->user()->id;
            $orderId            = $request->get('order_id');
            $remarks            = $request->get('remarks');
            $orderNumber        = $request->get('order_number');
            $issues             = $request->get('is_issue_identify');
            $uploadFolderPath   = config('constants.files.orders').'/'.$orderNumber;
            $thumbnailPath      = $uploadFolderPath.'/thumbnail';
            $orderUpdateArray   =  [ 'updated_at'=>now(), 'remarks' => $remarks ];

            if( $request->has('remarks_attachment') ){
                $attachment                      = $request->file('remarks_attachment');
                $newFileName                     =   $orderNumber.'-'.time().'-'.uniqid(rand(), true).'.' . $attachment->getClientOriginalExtension();
                $this->uploadMainImage( $attachment, $uploadFolderPath, $newFileName , $thumbnailPath );
                $orderUpdateArray["attachments"] = $newFileName;
            }

            $order = Order::where(['id' =>$orderId ])->first();

            $isToken = Arr::get($order, 'token');
            if( is_null($isToken) ){
                $token = sha1(uniqid(mt_rand(), true));
                $orderUpdateArray["token"]  = $token;
            }


            if( $request->has('image') ) {
                foreach ($request->file('image') as $itemId => $imageTypes) {
                    foreach ($imageTypes as $type => $files) {
                        if ($type == "pickup_images" || $type == "pickup_image") {
                            $imageType          = "Before Wash";
                            $mainImagePath      = $uploadFolderPath."/before";
                            $thumbnailImagePath = $thumbnailPath."/before";
                        }else if ($type == "delivery_images" || $type == "delivery_image") {
                            $imageType          = "After Wash";
                            $mainImagePath      = $uploadFolderPath."/after";
                            $thumbnailImagePath = $thumbnailPath."/after";
                        }

                        foreach ($files as $file) {
                            // Save file and process it
                            $newFileName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $file->getClientOriginalExtension();
                            $this->uploadMainImage( $file, $mainImagePath, $newFileName , $thumbnailImagePath );

                            $orderImages = [
                                'item_id'    => $itemId,
                                'image_type' => $imageType, // 'pickup_images' or 'delivery_images'
                                'imagename'  => $newFileName,
                                'admin_user' => $adminUser,
                                'status'     => 1,
                            ];

                            $ordersImagesModel = new OrderItemImage;
                            $imageItemId = $ordersImagesModel->createOrderItemImage($orderImages);

                            $data = [ 'image_type' => $imageType , 'imagename' => $newFileName  ];
                            $historyData[] = [
                                'order_id'      => $orderId,
                                'item_id'       => $itemId,
                                'item_image_id' => $imageItemId,
                                'action'        => "image_upload",
                                'admin_user'    => $adminUser,
                                'data' => json_encode($data)
                            ];
                        }
                    }
                }
            }


            try {
                $order->update(
                    $orderUpdateArray
                );

                if( !empty( $issues ) ){
                    foreach ( $issues as $key =>  $issue ){
                         OrderItem::where(['id' => $key ])->update(
                            [ "is_issue_identify" => $issue , 'updated_at'=>now() ]
                        );
                    }
                }

                $data = [ 'image_type' => isset( $orderUpdateArray["attachments"] )? 'Main Image':null  ,'remarks' => $remarks , 'imagename' => ($orderUpdateArray["attachments"] ?? null) , 'is_issue_identify' => $issues ];
                $historyData[] = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => "order_update",
                    'admin_user'    => $adminUser,
                    'data' => json_encode($data)
                ];

                $this->addHistory($historyData);

            }catch ( \Exception $exception ){
                die($exception->getMessage());
            }

            return redirect()->route('orders.edit', ['order_id' => $orderId])
                ->with('success', 'Order created successfully.');
        }

        return view('backend.orders.index');
    }

    public function createMissingThumbnail() {
        $orderItemImages = OrderItemImage::with('orderItem.order')
            ->where( 'status' ,'=' ,1 )->orderBy('id', 'asc')->get();

        foreach ($orderItemImages as $itemImage) {
            $type = ($itemImage->image_type == "Before Wash") ? "before" : "after";

            // Define paths for main image and thumbnail
            $mainImagePath = public_path(config('constants.files.orders')) . '/' .
                $itemImage->orderItem->order->order_id . '/' . $type . '/' . $itemImage->imagename;

            $thumbnailPath = public_path(config('constants.files.orders')) . '/' .
                $itemImage->orderItem->order->order_id . '/thumbnail/' . $type;

            $thumbnailImagePath = $thumbnailPath . '/' . $itemImage->imagename;

            // Check if the thumbnail exists
            if (!File::exists($thumbnailImagePath)) {
                // Create the thumbnail directory if it does not exist
                if (!File::exists($thumbnailPath)) {
                    File::makeDirectory($thumbnailPath, 0777, true, true);
                }

                // Ensure the main image exists before creating a thumbnail
                if (File::exists($mainImagePath)) {
                    $thumbnail = Image::make($mainImagePath)
                        ->resize(150, 150, function ($constraint) {
                            $constraint->aspectRatio(); // Maintain aspect ratio
                            $constraint->upsize();     // Prevent upsizing
                        });
                    // Save the thumbnail with 60% quality
                    $thumbnail->save($thumbnailImagePath, 60);
                } else {
                    // Log an error or handle missing main image
                    \Log::error("Main image not found: " . $mainImagePath);
                }
            }
        }
    }


    public function uploadMainImage( $file , $filePath , $filename , $thumbnailPath  ){
        $filePath               = public_path($filePath);
        $thumbnailPath          = public_path($thumbnailPath);
        if( !File::exists($filePath) ){
            File::makeDirectory($filePath,0777, true, true);
        }

        if( !File::exists($thumbnailPath) ){
            File::makeDirectory($thumbnailPath,0777, true, true);
        }

        //$image->move( $tempfilePath , $filename);
        $imageAttachmentItem = Image::make($file->getPathname());
        // Compress the image quality (e.g., 60%)
        $imageAttachmentItem->save($filePath . '/' . $filename, 60);


        $thumbnail = Image::make($file->getRealPath())
            ->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
                $constraint->upsize();     // Prevent upsizing
            });

        $thumbnail->save($thumbnailPath . '/' . $filename,60);
    }

    public function downloadImages($orderId=0,$folderName='',$orderToken='')
    {
        $message = '<p>Please feel free to contact us at 021-111-524-246 for any queries or concerns.</p>';
        //Check order token exist
        $order = Order::where(['order_id' =>$orderId])->first();
       if(!empty($order))
       {
            if($order->token === $orderToken)
            {
                $directoryPath = public_path("assets/uploads/orders/{$orderId}/{$folderName}");

                // Check if the directory exists
                if (!File::exists($directoryPath))
                {
                    echo "<p>The directory does not exist for order id:{$orderId}</p>".$message; exit;
                }

                // Get all files in the directory
                $files = File::files($directoryPath);
                if (empty($files))
                {
                    echo "<p>Unable to download. File not found for order id:{$orderId}</p>".$message; exit;
                }

                // Create a ZIP file
                $zipFileName = "{$folderName}_images.zip";
                $zipFilePath = storage_path("app/{$zipFileName}");

                $zip = new \ZipArchive;
                if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE)
                {
                    foreach ($files as $file) {
                        $zip->addFile($file->getRealPath(), $file->getFilename());
                    }
                    $zip->close();

                } else
                {
                    echo "<p>Failed to create the ZIP file for order id:{$orderId}. Please try again!</p>".$message; exit;
                }

                // Download the ZIP file
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            }
            else
            {
                echo "<p>Invalid url for order id:{$orderId}</p>".$message; exit;
            }
       }
       else
       {
            echo "<p>The order is invalid.!</p>".$message; exit;
       }



    }
}
