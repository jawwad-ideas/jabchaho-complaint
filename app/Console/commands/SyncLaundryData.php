<?php

namespace App\Console\commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class SyncLaundryData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:laundry-orders';

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
        try {
            $this->info('Laundry orders sync Start!');
            // Query the 'orders' table from the second database
            $orders = DB::connection('laundry_mysql')
                ->table('laundry_orders as o')
                ->select(
                    'o.id',
                    DB::raw("CONCAT(u.f_name, ' ', u.l_name) AS customer_name"),
                    'u.id as customer_id',
                    'u.phone as telephone',
                    'u.email as customer_email',
                    'o.created_at',
                    'o.picked_up as pick_date',
                    'o.delivery_date'
                )
                ->join('users as u', 'u.id', '=', 'o.user_id')
                ->where('o.order_status', 'processing')
                ->where('o.processing', '>=', DB::raw('NOW() - INTERVAL 1 HOUR'))
                ->orderBy('o.created_at', 'asc')
                ->get();


            // Process the orders
            if(!empty($orders))
            {
                foreach ($orders as $order)
                {

                    $insertData = [
                        'order_id' => $order->id,
                        'customer_id' =>$order->customer_id,
                        'customer_name' => $order->customer_name,
                        'telephone' => $order->telephone,
                        'customer_email' => $order->customer_email,
                        'created_at' => $order->created_at,
                        'pick_date' => $order->pick_date,
                        'delivery_date' => $order->delivery_date,
                    ];

                    $inserted = Order::insertGetId($insertData);
                    $this->info("inserted Order ID: {$inserted}");

////////////////////////////////////////////Item detail fetch Query/////////////////////////////////////////
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

                    //Item detail insert Query
                    if(!empty($orderItems))
                    {
                        foreach ($orderItems as $item)
                        {
                            $insertItemData = [
                                'order_id' => $inserted,
                                'service_type' => $item->service_type,
                                'barcode' => $item->barcode,
                                'item_name' => $item->item_name,
                                'qty' => $item->qty,
                                'laundry_item_id' =>$item->laundry_item_id
                            ];

                            $insertedItem = OrderItem::insertGetId($insertItemData);
                        }

                    }

                    $this->info("Processing Order ID: {$order->id}");


                }

                $this->info('Laundry orders synced successfully!');
            }
            else
            {
                $this->info('Order not found!');
            }

        } catch (\Exception $e)
        {
            $this->error('Error syncing orders: ' . $e->getMessage());

        }
    }


    public function manualSync()
    {
        try {
            // Query the 'orders' table from the second database
            $orders = DB::connection('laundry_mysql')
                ->table('laundry_orders as o')
                ->select(
                    'o.id',
                    DB::raw("CONCAT(u.f_name, ' ', u.l_name) AS customer_name"),
                    'u.id as customer_id',
                    'u.phone as telephone',
                    'u.email as customer_email',
                    'o.created_at',
                    'o.picked_up as pick_date',
                    'o.delivery_date'
                )
                ->join('users as u', 'u.id', '=', 'o.user_id')
                ->where('o.order_status', 'processing')
                ->where('o.processing', '>=', DB::raw('NOW() - INTERVAL 10000000 HOUR'))
                ->orderBy('o.created_at', 'asc')
                ->get();

            // Process the orders
            if(!empty($orders))
            {
                foreach ($orders as $order)
                {

                    $insertData = [
                        'order_id' => $order->id,
                        'customer_id' =>$order->customer_id,
                        'customer_name' => $order->customer_name,
                        'telephone' => $order->telephone,
                        'customer_email' => $order->customer_email,
                        'created_at' => $order->created_at,
                        'pick_date' => $order->pick_date,
                        'delivery_date' => $order->delivery_date,
                    ];

                    $inserted = Order::insertGetId($insertData);

////////////////////////////////////////////Item detail fetch Query/////////////////////////////////////////
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

                    //Item detail insert Query
                    if(!empty($orderItems))
                    {
                        foreach ($orderItems as $item)
                        {
                            $insertItemData = [
                                'order_id' => $inserted,
                                'service_type' => $item->service_type,
                                'barcode' => $item->barcode,
                                'item_name' => $item->item_name,
                                'qty' => $item->qty,
                                'laundry_item_id' =>$item->laundry_item_id
                            ];

                            $insertedItem = OrderItem::insertGetId($insertItemData);
                        }

                    }

                }
            }
            else
            {
            }
        } catch (\Exception $e)
        {
        }
    }

}
