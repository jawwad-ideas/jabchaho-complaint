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
use Illuminate\Support\Facades\URL;
use App\Models\OrderItemIssue;
use App\Jobs\SendWhatsAppJob as SendWhatsAppJob;

#use App\Models\OrdersImages;
class OrderController extends Controller
{

    public function uploadSave(Request $request)
    {
        dd($request->all());
    }

    public function uploadView(Request $request)
    {
        return view('backend.orders.temp');
    }

    public function itemImage(Request $request)
    {

        $orderItemImage = OrderItemImage::with(['orderItem.order','orderItem.issues'])
            ->where('status', 1)
            ->orderBy('id', 'desc');

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
            'issue_type' =>  $request->get('issue_type', '')
        ];

        // Apply filters based on request
        if ($filterData['barcode']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('barcode', 'like', '%' . $filterData['barcode'] . '%');
            });
        }

        if ($filterData['issue']) {
            $orderItemImage->whereHas('orderItem', function ($query) use ($filterData) {
                $query->where('is_issue_identify', '=',  $filterData['issue']);
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

            if ($filterData['location_type'] == strtolower(config('constants.laundry_location_type.store'))) {
                $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                    $query->whereNotNull('orders.location_type');
                });
            } else {
                $orderItemImage->whereHas('orderItem.order', function ($query) use ($filterData) {
                    $query->whereNull('orders.location_type');
                });
            }
        }

        if (!empty($filterData['issue_type'])) {
            //$orders->where('orderItems.issues', '=',  $issue_type);
            $orderItemImage->whereHas('orderItem.issues', function ($query) use ($filterData) {
                $query->where('issue', $filterData['issue_type']);
            }); // Filter orders where orderItems have issues of the given type
        }

        $orderItemImage = $orderItemImage->latest()->paginate(config('constants.per_page'));

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
        $issue_type         = $request->input('issue_type');

        //$orders = Order::select('*')->orderBy('id', 'desc');
        $orders = Order::withCount([
            'images', // Total images
            'before', // Count with image_type = 1
            'after', // Count with image_type = 2
            'orderItems as items_count', // Count of order items
        ])
            ->with(['orderItems.issues']) // Eager load order_items and their issues
            ->orderBy('id', 'desc');

        if (!empty($order_number)) {
            $orders->where('orders.order_id', '=',  $order_number);
        }

        if (!empty($customer_email)) {
            $orders->where('orders.customer_email', 'like',  '%' . $customer_email . '%');
        }

        if (!empty($customer_name)) {
            $orders->where('orders.customer_name', 'like',  '%' . $customer_name . '%');
        }

        if (!empty($after_email)) {
            $orders->where('orders.final_email', '=',  $after_email);
        }

        if (!empty($before_email)) {
            $orders->where('orders.before_email', '=',  $before_email);
        }


        if (!empty($telephone)) {
            $orders->where('orders.telephone', '=',  '%' . $telephone . '%');
        }

        if (!empty($status)) {
            $orders->where('orders.status', '=',  $status);
        }

        if (!empty($location_type)) {

            if ($location_type == strtolower(config('constants.laundry_location_type.store'))) {
                $orders->whereNotNull('orders.location_type');
            } else {
                $orders->whereNull('orders.location_type');
            }
        }

        if (!empty($issue_type)) {
            //$orders->where('orderItems.issues', '=',  $issue_type);
            $orders->whereHas('orderItems.issues', function ($query) use ($issue_type) {
                $query->where('issue', $issue_type);
            }); // Filter orders where orderItems have issues of the given type
        }

        $orders = $orders->latest()->paginate(config('constants.per_page'));
        //dd($orders);
        $filterData = [
            'order_number' => $order_number,
            'order_status' => $status,
            'customer_email' => $customer_email,
            'customer_name' => $customer_name,
            'telephone' => $telephone,
            'email_status_options' => [1 => "No", 2 => "Yes"],
            'before_email' => $before_email,
            'after_email' => $after_email,
            'location_type' => $location_type,
            'issue_type'    => $issue_type
        ];

        return view('backend.orders.index', compact('orders'))->with($filterData);
    }

    public function completeOrder(Request $request)
    {
        $orderId = $request->input('orderId');
        try {
            $order     = new Order();
            $order->where('id', $orderId)->first()->update(['updated_at' => now(), 'status' => 2]);

            try {
                $adminUser      = $request->user()->id;
                $historyData = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => 'order_complete',
                    'admin_user'    => $adminUser,
                    'data'          => null
                ];

                $this->addHistory($historyData);
            } catch (\Exception $exception) {
                die($exception->getMessage());
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendEmail(Request $request)
    {
        try {
            $orderId = $request->input('orderId');
            $emailType = $request->input('emailType');
            $data = null;
            if ($emailType == "before_email")
            {
                $orderUpdateArray["before_email"] = 2;
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
            dispatch(new SendEmailOnOrderCompletion($orderId, $emailType));
            $this->queueWorker();

            try {
                $adminUser      = $request->user()->id;
                $historyData = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => $emailType,
                    'admin_user'    => $adminUser,
                    'data'          => $data
                ];

                $this->addHistory($historyData);
            } catch (\Exception $exception) {
                die($exception->getMessage());
            }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function syncOrder(Request $request)
    {
        try {
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
            } catch (\Exception $exception) {
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
        },'orderItems.issues','orderItems.machineBarcode.machineDetail','orderItems.machineBarcode.machineDetail.machine','orderItems.machineBarcode.machineDetail.machineImages'])->find($orderId);

        

        $beforeEmailShow = $afterEmailShow = false;
        foreach ($order->orderItems as $item):
            foreach ($item->images as $image):
                if ($afterEmailShow && $beforeEmailShow):
                    break;
                endif;
                if ($image->image_type == "After Wash"):
                    $afterEmailShow = true;
                    break;
                elseif ($image->image_type == "Before Wash"):
                    $beforeEmailShow = true;
                endif;
            endforeach;
            if ($afterEmailShow && $beforeEmailShow):
                break;
            endif;
        endforeach;

        $disableAfterUploadInput = false;
        $orders = Order::with(['orderItems' => function ($query) {
            $query->whereDoesntHave('images', function ($imageQuery) {
                $imageQuery->where('image_type', 'Before Wash')->where('status', 1);
            });
        }])->find($orderId);
        $orderItemsWithoutBeforeImage = $orders->orderItems;
        if ( !$orderItemsWithoutBeforeImage->isEmpty()) {
            $disableAfterUploadInput = true;
        }

        return view('backend.orders.edit', [
            'order' => $order,
            'showCompleteButton'   => $afterEmailShow && $order->status != 2,
            'sendFinalEmail'       => $afterEmailShow,
            'sendFinalEmailTitle'       => ($order->final_email == 2) ? 'Resend Email After Wash' : 'Send Email After Wash',
            'sendBeforeEmail' => $beforeEmailShow,
            'sendBeforeEmailTitle' => ($order->before_email == 2) ? 'Resend Email Before Wash' : 'Send Email Before Wash',
            'disableAfterUploadInput' =>  $disableAfterUploadInput
        ]);
    }

    public function delete(Request $request)
    {
        $imageId = $request->input('imageId');
        $orderNumber = $request->input('orderNumber');

        try {
            $orderImagesModel = OrderItemImage::where('id', $imageId)->first();

            if ($orderImagesModel->image_type == "After Wash"):
                $folderName = 'after';
            elseif ($orderImagesModel->image_type == "Before Wash"):
                $folderName = 'before';
            endif;

            $directoryPath = public_path(config('constants.files.orders') . "/{$orderNumber}/{$folderName}");
            $realImage = $directoryPath . '/' . $orderImagesModel->imagename;
            $deleteDirectoryPath = public_path(config('constants.files.orders') . "/{$orderNumber}/delete");

            if (!File::exists($deleteDirectoryPath)) {
                File::makeDirectory($deleteDirectoryPath, 0777, true, true);
            }

            $deleteImage = $deleteDirectoryPath . '/' . $orderImagesModel->imagename;

            if (File::move($realImage, $deleteImage)) {
                if (File::exists($deleteImage)) {

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
                    } catch (\Exception $exception) {
                        die($exception->getMessage());
                    }

                    $orderImagesModel->update(['updated_at' => now(), 'status' => 0]);
                    return response()->json(['success' => true]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addHistory($historyData = [])
    {
        $ordersImagesModel = new OrderHistory();
        $ordersImagesModel->createOrderHistory($historyData);
    }

    public function uploadOrderImageWithoutBase64(Request $request)
    {
        try {
            $image = $thumbnail = $imageType = $imageTypeDirectory = '';
            $status = false;
            $imageItemId= 0;
            $message = "Oops! We couldn't upload your image. Please check the file and try again.";

            $type           = $request->imageType;
            $itemId         = $request->item_id;
            $orderNumber    = $request->order_num;
            $orderId        = $request->order_id;
            // Get the base64 string from the request
            $adminUser      = $request->user()->id;

            $uploadFolderPath   = config('constants.files.orders') . $orderNumber;
            $thumbnailPath      = $uploadFolderPath . '/thumbnail';

            if ($type == "pickup_images" || $type == "pickup_image") {
                $imageType          = "Before Wash";
                $imageTypeDirectory = "before";
                $mainImagePath      = $uploadFolderPath . "/" . $imageTypeDirectory;
                $thumbnailImagePath = $thumbnailPath . "/" . $imageTypeDirectory;
            } else if ($type == "delivery_images" || $type == "delivery_image") {
                $imageTypeDirectory = "after";
                $imageType          = "After Wash";
                $mainImagePath      = $uploadFolderPath . "/" . $imageTypeDirectory;
                $thumbnailImagePath = $thumbnailPath . "/" . $imageTypeDirectory;
            }
            $historyData = [];

            if( $request->has('image') ) {
                $file = $request->file('image');
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
                $this->addHistory($historyData);

                $status     = true;
                $message    = "Image uploaded successfully";
                $baseUrl = URL::to('/');
                $image      = $baseUrl . '/' . $mainImagePath . '/' . $newFileName;
                $thumbnail  =  $baseUrl . '/' . $thumbnailImagePath . '/' . $newFileName;
            }

            return response()->json([
                'success'                   => $status,
                'image_url'                 => $image,
                'thumbnail'                 => $thumbnail,
                'item_id'                   => $itemId,
                'imageType'                 => $type,
                'item_image_id'             => $imageItemId,
                'message'                   => $message
            ]);
        } catch (\Exception $e) {
            \Log::error("OrderController->uploadOrderImage->" . $e->getMessage());
            return false;
        }
    }
    public function save(OrderSaveRequest $request)
    {
        //dd( $request->all() );
        if ($request->has('order_id')) {
            $orderImages        = $historyData =  [];
            $adminUser          = $request->user()->id;
            $orderId            = $request->get('order_id');
            $remarks            = $request->get('remarks');
            $orderNumber        = $request->get('order_number');
            //$issues             = $request->get('is_issue_identify');
            $uploadFolderPath   = config('constants.files.orders') . '/' . $orderNumber;
            $thumbnailPath      = $uploadFolderPath . '/thumbnail';
            $orderUpdateArray   =  ['updated_at' => now(), 'remarks' => $remarks];

            if ($request->has('remarks_attachment')) {
                $attachment                      = $request->file('remarks_attachment');
                $newFileName                     =   $orderNumber . '-' . time() . '-' . uniqid(rand(), true) . '.' . $attachment->getClientOriginalExtension();
                $this->uploadMainImage($attachment, $uploadFolderPath, $newFileName, $thumbnailPath);
                $orderUpdateArray["attachments"] = $newFileName;
            }

            $order = Order::where(['id' => $orderId])->first();

            $isToken = Arr::get($order, 'token');
            if (is_null($isToken)) {
                $token = sha1(uniqid(mt_rand(), true));
                $orderUpdateArray["token"]  = $token;
            }

            try {
                $order->update(
                    $orderUpdateArray
                );

                // if (!empty($issues)) {
                //     foreach ($issues as $key =>  $issue) {
                //         OrderItem::where(['id' => $key])->update(
                //             ["is_issue_identify" => $issue, 'updated_at' => now()]
                //         );
                //     }
                // }

                $data = ['image_type' => isset($orderUpdateArray["attachments"]) ? 'Main Image' : null, 'remarks' => $remarks, 'imagename' => ($orderUpdateArray["attachments"] ?? null)];
                $historyData[] = [
                    'order_id'      => $orderId,
                    'item_id'       => null,
                    'item_image_id' => null,
                    'action'        => "order_update",
                    'admin_user'    => $adminUser,
                    'data' => json_encode($data)
                ];

                $this->addHistory($historyData);
            } catch (\Exception $exception) {
                die($exception->getMessage());
            }

            return redirect()->route('orders.edit', ['order_id' => $orderId])
                ->with('success', 'Order created successfully.');
        }

        return view('backend.orders.index');
    }

    public function createMissingThumbnail()
    {
        $orderItemImages = OrderItemImage::with('orderItem.order')
            ->where('status', '=', 1)->orderBy('id', 'asc')->get();

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


    public function uploadMainImage($file, $filePath, $filename, $thumbnailPath)
    {
        $filePath               = public_path($filePath);
        $thumbnailPath          = public_path($thumbnailPath);
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        if (!File::exists($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0777, true, true);
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

        $thumbnail->save($thumbnailPath . '/' . $filename, 60);
    }

    public function downloadImages($orderId = 0, $folderName = '', $orderToken = '')
    {
        $message = '<p>Please feel free to contact us at 021-111-524-246 for any queries or concerns.</p>';
        //Check order token exist
        $order = Order::where(['order_id' => $orderId])->first();
        if (!empty($order)) {
            if ($order->token === $orderToken) {
                $directoryPath = public_path("assets/uploads/orders/{$orderId}/{$folderName}");

                // Check if the directory exists
                if (!File::exists($directoryPath)) {
                    echo "<p>The directory does not exist for order id:{$orderId}</p>" . $message;
                    exit;
                }

                // Get all files in the directory
                $files = File::files($directoryPath);
                if (empty($files)) {
                    echo "<p>Unable to download. File not found for order id:{$orderId}</p>" . $message;
                    exit;
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
                    echo "<p>Failed to create the ZIP file for order id:{$orderId}. Please try again!</p>" . $message;
                    exit;
                }

                // Download the ZIP file
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                echo "<p>Invalid url for order id:{$orderId}</p>" . $message;
                exit;
            }
        } else {
            echo "<p>The order is invalid.!</p>" . $message;
            exit;
        }
    }


    public function uploadOrderImage(Request $request)
    {
        try {
            $image = $thumbnail = $imageType = $imageTypeDirectory = '';
            $status = false;
            $imageItemId= 0;
            $message = "Oops! We couldn't upload your image. Please check the file and try again.";

            $type           = $request->imageType;
            $itemId         = $request->item_id;
            $orderNumber    = $request->order_num;
            $orderId        = $request->order_id;
            // Get the base64 string from the request
            $base64Image    = $request->image_data;
            $adminUser      = $request->user()->id;

            $uploadFolderPath   = config('constants.files.orders') . $orderNumber;
            $thumbnailPath      = $uploadFolderPath . '/thumbnail';

            if ($type == "pickup_images" || $type == "pickup_image") {
                $imageType          = "Before Wash";
                $imageTypeDirectory = "before";
                $mainImagePath      = $uploadFolderPath . "/" . $imageTypeDirectory;
                $thumbnailImagePath = $thumbnailPath . "/" . $imageTypeDirectory;
            } else if ($type == "delivery_images" || $type == "delivery_image") {
                $imageTypeDirectory = "after";
                $imageType          = "After Wash";
                $mainImagePath      = $uploadFolderPath . "/" . $imageTypeDirectory;
                $thumbnailImagePath = $thumbnailPath . "/" . $imageTypeDirectory;
            }


            // Remove the base64 encoding prefix (data:image/png;base64, etc.)
            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

            if (!empty($imageData))
            {
                // Detect the image type (MIME type)
                $imageInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($imageInfo, $imageData);
                finfo_close($imageInfo);

                // Extract the image extension based on MIME type
                $imageExtension = null;
                switch ($mimeType) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $imageExtension = 'jpg';
                        break;
                    case 'image/png':
                        $imageExtension = 'png';
                        break;
                    case 'image/gif':
                        $imageExtension = 'gif';
                        break;
                    default:
                        return response()->json(['error' => 'Unsupported image type'], 400);
                }

                // Generate a unique name for the image
                $newFileName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $imageExtension;

                $result = $this->processBase64Image($base64Image, $mainImagePath, $thumbnailImagePath, $newFileName);



                if (!empty($result))
                {
                    $status     = true;
                    $message    = "Image uploaded successfully";
                    $image      = Arr::get($result, 'image');
                    $thumbnail  = Arr::get($result, 'thumbnail');


                    $orderImages = [
                        'item_id'    => $itemId,
                        'image_type' => $imageType, // 'pickup_images' or 'delivery_images'
                        'imagename'  => $newFileName,
                        'admin_user' => $adminUser,
                        'status'     => 1,
                    ];

                    $ordersImagesModel = new OrderItemImage;
                    $imageItemId = $ordersImagesModel->createOrderItemImage($orderImages);

                    $data = ['image_type' => $imageType, 'imagename' => $newFileName];

                    $historyData = [
                        'order_id'      => $orderId,
                        'item_id'       => $itemId,
                        'item_image_id' => $imageItemId,
                        'action'        => "image_upload",
                        'admin_user'    => $adminUser,
                        'data' => json_encode($data)
                    ];

                    $this->addHistory($historyData);
                }
            }

            $disableAfterUploadInput = false;
            $orders = Order::with(['orderItems' => function ($query) {
                $query->whereDoesntHave('images', function ($imageQuery) {
                    $imageQuery->where('image_type', 'Before Wash')->where('status', 1);
                });
            }])->find($orderId);
            $orderItemsWithoutBeforeImage = $orders->orderItems;
            if ( !$orderItemsWithoutBeforeImage->isEmpty()) {
                $disableAfterUploadInput = true;
            }


            return response()->json([
                'success'                   => $status,
                'image_url'                 => $image,
                'thumbnail'                 => $thumbnail,
                'item_id'                   => $itemId,
                'imageType'                 => $type,
                'item_image_id'             => $imageItemId,
                'message'                   => $message,
                'disableAfterUploadInput'  => $disableAfterUploadInput
            ]);
        } catch (\Exception $e) {
            \Log::error("OrderController->uploadOrderImage->" . $e->getMessage());
            return false;
        }
    }



    // Function to process Base64 image
    function processBase64Image($base64Image, $filePath, $thumbnailPath, $filename)
    {
        try {
            $baseUrl = URL::to('/');
            // Decode Base64 string
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            // Ensure directories exist
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            if (!File::exists($thumbnailPath)) {
                File::makeDirectory($thumbnailPath, 0777, true, true);
            }

            // Save the compressed image
            $imageAttachmentItem = Image::make($imageData);
            $imageAttachmentItem->save($filePath . '/' . $filename); // Compress to 60% quality

            // Create a thumbnail
            $thumbnail = Image::make($imageData)
                ->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                    $constraint->upsize();     // Prevent upsizing
                });
            $thumbnail->save($thumbnailPath . '/' . $filename); // Save thumbnail with compression

            return [
                'image' => $baseUrl . '/' . $filePath . '/' . $filename,
                'thumbnail' => $baseUrl . '/' . $thumbnailPath . '/' . $filename,
            ];
        } catch (\Exception $e) {
            \Log::error("OrderController->processBase64Image->" . $e->getMessage());
            return false;
        }
    }


    public function saveItemIssue(Request $request)
    {
        try
        {

            $itemId             = $request->itemId;
            $itemIssueList      = $request->itemIssueList;

            //delete all record w.r.t to item
            OrderItemIssue::where(['item_id'=> $itemId])->delete();

            //then insert record
            $orderItemIssue = array();
            if(!empty($itemIssueList))
            {
                foreach($itemIssueList as $itemissue)
                {
                    $orderItemIssue[] = array('item_id'=>$itemId, 'issue' =>$itemissue);
                }
            }

            $isInserted = OrderItemIssue::insert($orderItemIssue);

            if ($isInserted)
            {
                //update order item field is_issue_identify
                OrderItem::where('id', $itemId)->update(['is_issue_identify' => 2]);

                return response()->json([
                    'status'                   => true,
                    'message'                  => "Issues Saved successfully!!"
                ]);
            } else
            {
                return response()->json([
                    'status'                   => false,
                    'message'                  => "Issues were not saved successfully. Please try again!"
                ]);
            }



        } catch (\Exception $e) {
            \Log::error("OrderController->saveItemIssue->" . $e->getMessage());
            return false;
        }
    }


    public function removeItemIssue(Request $request)
    {
        try
        {
            $itemId             = $request->itemId;

            //delete all record w.r.t to item
            OrderItemIssue::where(['item_id'=> $itemId])->delete();

            //update order item field is_issue_identify
            OrderItem::where('id', $itemId)->update(['is_issue_identify' => 1]);

            return response()->json([
                'status'                   => true,
            ]);


        } catch (\Exception $e) {
            \Log::error("OrderController->removeItemIssue->" . $e->getMessage());
            return false;
        }
    }


    public function isItemIssueFixed(Request $request)
    {
        try
        {
            $itemId             = $request->itemId;
            $isIssueFixed       = $request->isIssueFixed;


            //update order item field is_issue_identify is yes mean 2
            if($isIssueFixed == 2)
            {
                OrderItem::where(['id'=> $itemId, 'is_issue_identify' => 2])->update(['is_issue_fixed' => $isIssueFixed]);
            }else
            {
                OrderItem::where(['id'=> $itemId])->update(['is_issue_fixed' => $isIssueFixed]);
            }


            return response()->json([
                'status'                   => true,
            ]);


        } catch (\Exception $e) {
            \Log::error("OrderController->removeItemIssue->" . $e->getMessage());
            return false;
        }
    }

    public function barcodeImageUpload(Request $request)
    {
        try
        {
            $orderItem = array();
            $barcode            = $request->input('barcode');

            if(!empty($barcode))
            {
                $orderItem = OrderItem::with(['images' => function ($query) {
                    $query->where('status', 1);
                },'order','issues','machineBarcode.machineDetail','machineBarcode.machineDetail.machine','machineBarcode.machineDetail.machineImages'])->where('barcode', 'like', '%'.$barcode.'%')->take(10)->get();
            }

            $data['barcode']    = $barcode;  
            $data['items']       = $orderItem;
            
            return view('backend.orders.barcodeImageUpload')->with($data);

        } catch (\Exception $e) {
            \Log::error("OrderController->barcodeImageUpload->" . $e->getMessage());
            return false;
        }
    }

    //show image
    public function showImage($orderNo, $folder, $imageName)
    {
        // Define the paths
        $imagePath = public_path("assets/uploads/orders/{$orderNo}/{$folder}/{$imageName}");
        $fallbackImagePath = public_path("assets/uploads/orders/{$orderNo}/thumbnail/{$folder}/{$imageName}");
        $defaultPlaceholder = public_path('assets/uploads/default-placeholder.png'); // Your fallback placeholder image
        
        // Check if the original image exists
        if (file_exists($imagePath)) {
            return response()->file($imagePath); // Return the original image
        }

        // If the original file doesn't exist, check for the fallback image
        if (file_exists($fallbackImagePath)) {
            return response()->file($fallbackImagePath); // Return the fallback image
        }

        // If neither file exists, return a default placeholder image
        return response()->file($defaultPlaceholder); // Return a default placeholder
    }

    public function sendWhatsApp(Request $request)
    {
        try {
            $orderId        = $request->input('orderId');
            $orderNumber    = $request->input('orderNumber');
            $whatsAppType   = $request->input('whatsAppType');

            $order = Order::where(['id' => $orderId])->first();


            $params['orderId']              = $orderId;  
            $params['orderNumber']          = $orderNumber;
            $params['whatsAppType']         = $whatsAppType;
            $params['order']                = $order;

            //SendWhatsApp Queue Called.
            dispatch(new SendWhatsAppJob($params));
            $this->queueWorker();

            //$data = null;
            // if ($emailType == "before_email")
            // {
            //     $orderUpdateArray["before_email"] = 2;
            // } else {
            //     $orderUpdateArray["final_email"] = 2;
            // }

            // $orderUpdateArray["updated_at"] = now();
            // //Order Update
            // $order = Order::where(['id' => $orderId])->first();
            // $order->update(
            //     $orderUpdateArray
            // );

            //email Queue Called.
            // dispatch(new SendWhatsAppJob($orderId, $emailType));
            // $this->queueWorker();

            // try {
            //     $adminUser      = $request->user()->id;
            //     $historyData = [
            //         'order_id'      => $orderId,
            //         'item_id'       => null,
            //         'item_image_id' => null,
            //         'action'        => $emailType,
            //         'admin_user'    => $adminUser,
            //         'data'          => $data
            //     ];

            //     $this->addHistory($historyData);
            // } catch (\Exception $exception) {
            //     die($exception->getMessage());
            // }


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchOrderDetail($orderId=0)
    {
        try
        {
            $status = false;
            $order = [];
            
            $orderModel = Order::where('order_id', $orderId)->select('customer_name','customer_email','telephone')->first();
            
            if ($orderModel) {
                $order = $orderModel->toArray();
                $status = true;
            }
            
            return response()->json([
                'success' => $status,
                'order'   => $order
            ]);

        } catch (\Exception $e) {
            \Log::error("OrderController->fetchOrderDetail->" . $e->getMessage());
            return false;
        }
    }
}
