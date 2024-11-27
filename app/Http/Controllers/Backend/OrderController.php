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

        $orders = Order::select('*')->orderBy('id', 'desc');

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
            $order->where('id',$orderId )->first()->update(['updated_at'=>now(),'status' => 2  ]);

            // Dispatch job to send emails
            dispatch(new SendEmailOnOrderCompletion($orderId));
            $this->queueWorker();

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
        try {
            $orderImagesModel     = new OrderItemImage();
            $orderImagesModel->where('id',$imageId)->first()->update(['updated_at'=>now(),'status'=>0]);
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
                $orderUpdateArray  = [ "attachments"=>$attachmentName ];
            }

            $order     = new Order();
            $order->where('id',$orderId)->first()->update(
                $orderUpdateArray
            );


            if( $request->has('image') ) {
                foreach ($request->file('image') as $itemId => $imageTypes) {
                    foreach ($imageTypes as $type => $files) {
                        if ($type == "pickup_images")
                            $imageType = "Before Wash";
                        else if ($type == "delivery_images")
                            $imageType = "After Wash";

                        foreach ($files as $file) {
                            // Save file and process it
                            $image = $file;
                            $newName = $orderNumber . '-' . $itemId . '-' . time() . '-' . uniqid(rand(), true) . '.' . $image->getClientOriginalExtension();
                            $image->move($filePath, $newName);

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
}
