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
use App\Models\OrderItem;
use App\Models\OrderItemImage;
use Illuminate\Http\Request;


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
        $validated = $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = trim($validated['barcode']);

        $item = OrderItem::select('id', 'barcode')
            ->with(['images' => function ($q) {
                $q->select('id', 'item_id', 'imagename', 'image_type')
                ->where('status', 1);
            }])
            ->where('barcode', $barcode)
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found for this barcode.',
                'data' => [
                    'before_wash_images' => [],
                    'after_wash_images' => [],
                ]
            ], 404);
        }

        $beforeWashImages = [];
        $afterWashImages  = [];

        foreach ($item->images as $image) {
            if ($image->image_type === 'Before Wash') {
                $beforeWashImages[] = $image->imagename;
            } elseif ($image->image_type === 'After Wash') {
                $afterWashImages[] = $image->imagename;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'item_id' => $item->id,
                'barcode' => $item->barcode,
                'before_wash_images' => $beforeWashImages,
                'after_wash_images' => $afterWashImages,
            ]
        ]);
    }

}
