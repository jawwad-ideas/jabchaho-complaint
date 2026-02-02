<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Models\Review;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Requests\Api\TrackComplaintRequest;
use App\Http\Requests\Api\ReviewRequest;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Traits\Configuration\ConfigurationTrait;
use App\Jobs\NotifyComplainant as NotifyComplainant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    use ConfigurationTrait;
    /**
     * create Order.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(CreateOrderRequest $request)
    {
        $validateValues                     = $request->validated();
        
        \Log::info('Order Api Call');
        \Log::info(''.print_r($validateValues,true));
        
        $responseStatus                     = true;
        try
        {
            $responsearray                      = array();
            $responsearray['status'] 	        = $responseStatus;
            $responsearray['message'] 	        = 'Order Successfully Created';
        }
        catch(\Exception $e) {
            $responsearray['message'] 	        = 'Error Submitting Complaint '.$e->getMessage();
            $responsearray['status'] 	        = false;
        }

        //$responsearray['request']           = $validateValues;//json_decode($request->getContent(),true);
        return response()->json($responsearray);
    }

    public function getOrderItemImages(Request $request)
    {
        try {

            $validated = $request->validate([
                'barcode' => 'required|string',
            ]);

            $barcode = trim($validated['barcode']);

            $order = Order::with([
                    'orderItems' => function ($q) use ($barcode) {
                        $q->where('barcode', $barcode)
                        ->with(['images' => function ($imgQ) {
                            $imgQ->where('status', 1);
                        }]);
                    }
                ])
                ->whereHas('orderItems', function ($q) use ($barcode) {
                    $q->where('barcode', $barcode);
                })
                ->first();

            if (!$order || $order->orderItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found for this barcode.',
                    'data' => [
                        'before_wash_images' => [],
                        'after_wash_images'  => [],
                    ]
                ], 404);
            }

            $item = $order->orderItems->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order item missing.',
                    'data' => []
                ], 404);
            }

            $itemId  = $item->id;
            $barcode = $item->barcode;
            $service = $item->service_type;
            $product = $item->item_name;

            $orderNo = $order->order_id;

            $beforeWashImages = [];
            $afterWashImages  = [];

            
            $baseDir = config('constants.files.orders').$orderNo;

            foreach ($item->images as $image) {

                if (!in_array($image->image_type, ['Before Wash', 'After Wash'])) {
                    continue;
                }

                $typeFolder = ($image->image_type === 'After Wash') ? 'after' : 'before';
                $fileName   = $image->imagename;

                $thumbRel = "{$baseDir}/thumbnail/{$typeFolder}/{$fileName}";

                $imageUrl = url($thumbRel);

                if ($image->image_type === 'Before Wash') {
                    $beforeWashImages[] = $imageUrl;
                } else {
                    $afterWashImages[] = $imageUrl;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id'  => $orderNo, 
                    'item_id'   => $itemId,
                    'barcode'   => $barcode,
                    'service'   => $service,
                    'product'   => $product,
                    'before_wash_images' => $beforeWashImages,
                    'after_wash_images'  => $afterWashImages,
                ]
            ]);

        } catch (\Throwable $e) {

            Log::error('getOrderItemImages error', [
                'barcode' => $request->barcode ?? null,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching item images.',
            ], 500);
        }
    }


}
