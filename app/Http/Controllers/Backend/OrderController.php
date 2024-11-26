<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

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
#use App\Models\OrdersImages;
class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orderNumber = $request->input('order_number');
        //$orders = Orders::with('images')->select('*')->orderBy('id', 'desc');
        $orders = new Order;
        //$orders = Orders::->select('*')->orderBy('id', 'desc');
        /*$orders = Orders::withCount([ 'images as before' => function ($query) {
            $query->where('image_type', 'Before Wash')->where('status', 1);
        }, 'images as after' => function ($query) {
            $query->where('image_type', 'After Wash')->where('status', 1);
        }, ]);*/

        dd( $orders );
        if (!empty($orderNumber)) {
            $orders->where('orders.order_number', '=',  $orderNumber );
        }

        $orders = $orders->latest()->paginate(config('constants.per_page'));


        $filterData = [
            'order_number' => $orderNumber
        ];

        return view('backend.orders.index', compact('orders'))->with($filterData);
    }

    public function create()
    {
        return view('backend.orders.create');
    }

    public function edit($orderId)
    {
        $order =  Orders::with(['images' => function ($query) {
            $query->where('status', 1);
        }])
            ->where('id', $orderId)
            ->first();

        return view('backend.orders.edit', [
            'order' => $order
        ]);
    }

    public function delete( $order_id ,  $image_id )
    {
        $orderImagesModel     = new OrdersImages();
        $orderImagesModel->where('id',$image_id)->first()->update(['updated_at'=>now(),'status'=>0]);

        return redirect()->route('orders.edit', ['order_id' => $order_id])
            ->with('success', 'Image deleted successfully.');

    }

    public function save( Request $request )
    {
        if( $request->has('order_number') )
        {
            $orderDate = $orderImages = [];
            $orderNumber    = $request->get('order_number');
            $adminUser      = $request->user()->id;
            $orderModel     = new Orders;
            $order          = $orderModel::select('order_number','id')->where('order_number', '=' , $orderNumber )->latest()->first();

            if( !$order ){
                $orderDate = [
                    'order_number' => $orderNumber,
                    'adminuser'   => $adminUser,
                ];
                $orderId = $orderModel->createOrder( $orderDate );
            }else{
                $orderId = $order->id;
                $order->update(['updated_at'=>now()]);
            }

            if( $orderId ){
                $ordersImagesModel = new OrdersImages;
                $uploadFolderPath = config('constants.files.orders').'/'.$orderNumber;
                $filePath = public_path($uploadFolderPath);

                $data = [
                    'order_id'      => $orderId,
                    'adminuser'     => $adminUser,
                    'file_path'     => $filePath,
                    'type'          => '',
                    'order_number'  => $orderNumber,
                ];

                if($request->hasfile('pickup_images')) {
                    $data['type'] = 'Before Wash';
                    $files = $request->file('pickup_images');
                    $orderImages = $this->uploadImage( $files , $data , $orderImages );
                }

                if($request->hasfile('delivery_images')) {
                    $data['type'] = 'After Wash';
                    $files = $request->file('delivery_images');
                    $orderImages = $this->uploadImage( $files , $data , $orderImages );
                }

                if( !empty( $orderImages ) ){
                    $ordersImagesModel->createOrderImage($orderImages);
                }
            }

            return redirect()->route('orders.edit', ['order_id' => $orderId])
                ->with('success', 'Order created successfully.');
        }

        return view('backend.orders.create');
    }

    public function uploadImage( $images , $data  , $orderImages = [] ){
        $orderNumber    =   $data['order_number'];
        $filePath       =   $data['file_path'];
        $adminUser      =   $data['adminuser'];
        $orderId        =   $data['order_id'];
        $type           =   $data['type'];
        foreach ( $images as $file ) {
            $image      =   $file;
            $newName    =   $orderNumber.'-'.now()->format('Y-m-d-h:i:s A').'-'.uniqid(rand(), true).'.' . $image->getClientOriginalExtension();
            $image->move($filePath, $newName);
            $orderImages[] = [
                'order_id'      => $orderId,
                'image_type'    => $type,
                'adminuser'     => $adminUser,
                'filename'      => $newName,
                'image_path'    => $newName,
            ];
        }
        return $orderImages;
    }
}
