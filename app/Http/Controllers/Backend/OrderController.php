<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\OrderItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
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

    public function index(Request $request)
    {
        $order_number       = $request->input('order_number');
        $customer_email     = $request->input('customer_email');
        $customer_name      = $request->input('customer_name');
        $telephone          = $request->input('telephone');
        $before_email          = $request->input('before_email');
        $after_email          = $request->input('after_email');
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
        ];

        return view('backend.orders.index', compact('orders'))->with($filterData);
    }

    public function completeOrder( Request $request )
    {
        $orderId = $request->input('orderId');
        try {
            $order     = new Order();
            $order->where('id',$orderId )->first()->update( [ 'updated_at'=>now() , 'status' => 2 ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendEmail( Request $request ){
        try {
            $orderId = $request->input('orderId');
            $emailType = $request->input('emailType');

            if ($emailType == "before_email") {
                $remarks = $request->input('remarks');
                $itemsIssuesl = $request->input('itemsIssues');
                $orderUpdateArray["before_email"] = 2;
                $orderUpdateArray["before_email_remarks"] = $remarks;
                $orderUpdateArray["before_email_options"] = $itemsIssuesl;
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
                    $orderImagesModel->update(['updated_at'=>now(),'status'=>0]);
                    return response()->json(['success' => true]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function save( OrderSaveRequest $request )
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

                // Compress and save the image
                $imageAttachment = Image::make($attachment->getPathname());
                // Compress the image quality (e.g., 75%)
                $imageAttachment->save($filePath . '/' . $attachmentName, 60);

                //$attachment->move( $filePath, $attachmentName );
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
                        if ($type == "pickup_images" || $type == "pickup_image") {
                            $imageType = "Before Wash";
                            $tempfilePath           = $uploadFolderPath."/before";
                            $tempfilePath           = public_path($tempfilePath);

                            if( !File::exists($tempfilePath) ){
                                File::makeDirectory($tempfilePath,0777, true, true);
                            }

                        }else if ($type == "delivery_images" || $type == "delivery_image") {
                            $imageType = "After Wash";
                            $tempfilePath           = $uploadFolderPath."/after";
                            $tempfilePath           = public_path($tempfilePath);

                            if( !File::exists($tempfilePath) ){
                                File::makeDirectory($tempfilePath,0777, true, true);
                            }
                        }


                        foreach ($files as $file) {
                            // Save file and process it
                            $imageItem = $file;
                            $newName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $imageItem->getClientOriginalExtension();
                            //$image->move( $tempfilePath , $newName);

                            $imageAttachmentItem = Image::make($imageItem->getPathname());
                            // Compress the image quality (e.g., 75%)
                            $imageAttachmentItem->save($tempfilePath . '/' . $newName, 60);

                            $orderImages[] = [
                                'item_id' => $itemId,
                                'image_type' => $imageType, // 'pickup_images' or 'delivery_images'
                                'imagename' => $newName,
                                'admin_user' => $adminUser,
                                'status' => 1,
                            ];
                        }

                        //Capture Images Only
                        /*if( $type == "pickup_image" || $type == "delivery_image" ){
                            $imageItem = $files;
                            $newName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $imageItem->getClientOriginalExtension();
                            //$image->move( $tempfilePath , $newName);

                            $imageAttachmentItem = Image::make($imageItem->getPathname());
                            // Compress the image quality (e.g., 75%)
                            $imageAttachmentItem->save($tempfilePath . '/' . $newName, 60);


                            $orderImages[] = [
                                'item_id' => $itemId,
                                'image_type' => $imageType, // 'pickup_images' or 'delivery_images'
                                'imagename' => $newName,
                                'admin_user' => $adminUser,
                                'status' => 1,
                            ];

                        }*/
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
