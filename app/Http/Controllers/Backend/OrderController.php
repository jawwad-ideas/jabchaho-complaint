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
#use App\Models\OrdersImages;
class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orderNumber = $request->input('order_number');

        $orders = Order::select('*')->orderBy('id', 'desc');

        if (!empty($orderNumber)) {
            $orders->where('orders.order_id', '=',  $orderNumber );
        }

        $orders = $orders->latest()->paginate( config('constants.per_page') );
        //dd($orders);
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
        $order = Order::with(['orderItems.images'])->find($orderId);

        //dd($order);

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
        if ( $request->has('order_id') && $request->has('image')) {
            $orderImages = [];
            $adminUser      = $request->user()->id;
            $orderId        = $request->get('order_id');
            $orderNumber    = $request->get('order_number');
            foreach ($request->file('image') as $itemId => $imageTypes) {
                foreach ($imageTypes as $type => $files) {
                    if( $type == "pickup_images")
                        $imageType = $type;
                    else if( $type == "delivery_images" )
                        $imageType = $type;

                    foreach ($files as $file) {
                        // Save file and process it
                        $uploadFolderPath = config('constants.files.orders').'/'.$orderNumber;
                        $filePath = public_path($uploadFolderPath);
                        $image      =   $file;
                        $newName    =   $orderNumber.'-'.$itemId.'-'.time().'-'.uniqid(rand(), true).'.' . $image->getClientOriginalExtension();
                        $image->move( $filePath, $newName );

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

            if( !empty($orderImages) ){
                $ordersImagesModel = new OrderItemImage;
                $ordersImagesModel->createOrderItemImage($orderImages);

                return redirect()->route('orders.edit', ['order_id' => $orderId ])
                    ->with('success', 'Order created successfully.');

            }
        }
        return view('backend.orders.index');
    }

}
