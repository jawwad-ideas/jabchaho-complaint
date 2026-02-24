<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class SyncSingleLaundryOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-single-laundry-order {order_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $laundryOrderId = $this->argument('order_id');

        try {
            $this->info("Single Laundry order sync start! Order ID: {$laundryOrderId}");

            // Fetch single order from laundry DB
            $order = DB::connection('laundry_mysql')
                ->table('laundry_orders as o')
                ->select(
                    'o.id',
                    DB::raw("CONCAT(u.f_name, ' ', u.l_name) AS customer_name"),
                    'u.id as customer_id',
                    'u.phone as telephone',
                    'u.email as customer_email',
                    'o.created_at',
                    'o.picked_up as pick_date',
                    'o.delivery_date',
                    'o.store_id',
                    'o.order_status'
                )
                ->join('users as u', 'u.id', '=', 'o.user_id')
                ->where('o.id', $laundryOrderId)
                ->first();

            if (!$order) {
                $this->error("Order not found in laundry DB. Order ID: {$laundryOrderId}");
                return SymfonyCommand::FAILURE;
            }

            $orderModel = Order::updateOrCreate(
                ['order_id' => $order->id], // condition
                [
                    'customer_id'    => $order->customer_id,
                    'customer_name'  => $order->customer_name,
                    'telephone'      => $order->telephone,
                    'customer_email' => $order->customer_email,
                    'pick_date'      => $order->pick_date,
                    'delivery_date'  => $order->delivery_date,
                    'location_type'  => $order->store_id,
                ]
            );
            $localOrderId = $orderModel->id;
            $this->info("Order synced. Local ID: {$localOrderId}");

            // Fetch order items from laundry DB
            $orderItems = DB::connection('laundry_mysql')
                ->table('laundry_order_details as od')
                ->join('laundry_item_details_tracks as odt', 'odt.laundry_order_detail_id', '=', 'od.id')
                ->join('laundry_items as i', 'i.id', '=', 'od.laundry_item_id')
                ->join('services as s', 's.id', '=', 'od.services_id')
                ->select(
                    'od.laundry_item_id',
                    'odt.bar_code as barcode',
                    's.name as service_type',
                    'od.quantity as qty',
                    'i.name as item_name'
                )
                ->where('od.laundry_orders_id', $order->id)
                ->get();

            $this->info('orderItems=>' . json_encode($orderItems));

            if ($orderItems->count() > 0) {
                foreach ($orderItems as $item) {
                    try {
                        OrderItem::updateOrCreate(
                            [
                                'order_id' => $localOrderId,
                                'barcode'  => $item->barcode,   // unique condition
                            ],
                            [
                                'service_type'    => $item->service_type,
                                'item_name'       => $item->item_name,
                                'qty'             => $item->qty,
                                'laundry_item_id' => $item->laundry_item_id,
                            ]
                        );
                        // OrderItem::insertGetId($insertItemData);
                    } catch (\Exception $e) {
                        \Log::error("SyncSingleLaundryOrder -> OrderItem::insert => " . $e->getMessage());
                        $this->warn("Item insert failed for barcode: {$item->barcode} (check logs)");
                        continue;
                    }
                }
                // Fetch barcodes from laundry items
                $incomingBarcodes = $orderItems->pluck('barcode')->filter()->values()->all();

                // Upsert items
                foreach ($orderItems as $item) {
                    OrderItem::updateOrCreate(
                        [
                            'order_id' => $localOrderId,
                            'barcode'  => $item->barcode,
                        ],
                        [
                            'service_type'    => $item->service_type,
                            'item_name'       => $item->item_name,
                            'qty'             => $item->qty,
                            'laundry_item_id' => $item->laundry_item_id,
                        ]
                    );
                }

                // ✅ Delete removed items from local DB
                OrderItem::where('order_id', $localOrderId)
                    ->whereNotIn('barcode', $incomingBarcodes)
                    ->delete();

                if ($orderItems->count() === 0) {
                    OrderItem::where('order_id', $localOrderId)->delete();
                    $this->warn("No items found, local items deleted for order: {$localOrderId}");
                }
            } else {
                $this->warn("No items found for laundry order ID: {$order->id}");
            }

            $this->info("Single Laundry order sync completed! Laundry Order ID: {$order->id}");
            return SymfonyCommand::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error syncing order: ' . $e->getMessage());
            \Log::error("SyncSingleLaundryOrder -> handle => " . $e->getMessage());
            return SymfonyCommand::FAILURE;
        }
    }
}

