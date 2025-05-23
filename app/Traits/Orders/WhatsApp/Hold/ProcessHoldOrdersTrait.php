<?php

namespace App\Traits\Orders\WhatsApp\Hold;

use Illuminate\Support\Arr;
use App\Jobs\SendWhatsAppJob;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;

trait ProcessHoldOrdersTrait
{
    protected function processAndReleaseHoldOrders($order = null, $whatsAppType = null, $data=null)
    {
        $orderId        = Arr::get($order, 'id');
        $orderNumber    = Arr::get($order, 'order_id');

        $params = [
            'orderId'       => $orderId,
            'orderNumber'   => $orderNumber,
            'whatsAppType'  => $whatsAppType,
            'order'         => $order,
        ];

        $orderUpdateArray = [
            $whatsAppType => 2,
            "updated_at"  => now(),
        ];

        $order->update($orderUpdateArray);

        try {
            $ordersImagesModel = new OrderHistory();
            $adminUser      = optional(Auth::user())->id ?? 0;;

            $ordersImagesModel->createOrderHistory([
                'order_id'      => $orderId,
                'item_id'       => null,
                'item_image_id' => null,
                'action'        => $whatsAppType,
                'admin_user'    => $adminUser,
                'data'          => $data
            ]);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }

        dispatch(new SendWhatsAppJob($params));
        
        return true;
    }
}