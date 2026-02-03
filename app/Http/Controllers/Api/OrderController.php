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
use Illuminate\Support\Facades\Log;


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
                'item_bar_codes'   => 'required|array|min:1',
                'item_bar_codes.*' => 'required|string',
            ]);

            $barcodes = collect($validated['item_bar_codes'])
                ->map(fn ($b) => trim($b))
                ->filter()
                ->unique()
                ->values();

            $items = OrderItem::with(['images' => function ($imgQ) {
                    $imgQ->where('status', 1)
                        ->where('image_type', 'After Wash')
                        ->orderBy('id', 'asc');
                }, 'order'])
                ->whereIn('barcode', $barcodes)
                ->get()
                ->keyBy('barcode');

            $data = [];

            foreach ($barcodes as $barcode) {
                $item = $items->get($barcode);

                if (!$item) {
                    $data[] = [
                        'barcode' => $barcode,
                        'found'   => false,
                        'after_wash_image' => null,
                    ];
                    continue;
                }

                $firstAfter = $item->images->first();

                $afterUrl = null;
                if ($firstAfter && $item->order) {
                    $orderNo  = $item->order->order_id ?? $item->order->id;
                    $baseDir  = config('constants.files.orders') . $orderNo;
                    $fileName = $firstAfter->imagename;
                    $afterUrl = url("{$baseDir}/thumbnail/after/{$fileName}");
                }

                $data[] = [
                    'barcode' => $barcode,
                    'found'   => true,
                    'item_id' => $item->id,
                    'order_id' => optional($item->order)->order_id,
                    'service' => $item->service_type ?? null,
                    'product' => $item->item_name ?? null,
                    'after_wash_image' => $afterUrl,
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching item images.',
            ], 500);
        }
    }




}
