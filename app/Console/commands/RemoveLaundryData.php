<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use App\Http\Traits\Configuration\ConfigurationTrait;

class RemoveLaundryData extends Command
{
    use ConfigurationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:laundry-orders';

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
        try
        {
            $orderNos = []; // Initialize the array to store order IDs
            
            //configuration filters
            $filters            = ['laundry_order_cron_enable','laundry_order_delete_days_from_now'];
            
            //get configurations
            $configurations     = $this->getConfigurations($filters);
      
            //Check cron is enabled or not
            if(!empty($configurations['laundry_order_cron_enable']))
            {  
                $numberOfDays = Arr::get($configurations, 'laundry_order_delete_days_from_now');
                $date = date('Y-m-d', strtotime('-'.$numberOfDays.' days'));
            
                // Wrap the deletion process in a transaction to ensure consistency
                DB::transaction(function () use ($date, &$orderNos) {
                    // Find orders to be deleted
                    $orders = Order::where('created_at', '<', $date . ' 23:59:59')->get();

                    // Extract order IDs
                    $orderIds = $orders->pluck('id')->toArray();
                    $orderNos = $orders->pluck('order_id')->toArray();

                    if (!empty($orderIds)) 
                    {
                        // Get item IDs from order_items
                        $itemIds = OrderItem::whereIn('order_id', $orderIds)->pluck('id')->toArray();
                
                        // Delete related order_item_images
                        if (!empty($itemIds)) {
                            OrderItemImage::whereIn('item_id', $itemIds)->delete();
                        }
                
                        // Delete order_items
                        OrderItem::whereIn('order_id', $orderIds)->delete();
                
                        // Delete orders
                        Order::whereIn('id', $orderIds)->delete();
                    }
                });

                // Now you can use $orderNos outside the transaction block
                if(!empty($orderNos))
                {
                    foreach ($orderNos as $orderNo) 
                    {
                        $folderPath = public_path("assets/uploads/orders/$orderNo");
            
                        if (File::exists($folderPath)) 
                        {
                            File::deleteDirectory($folderPath);
                        } else 
                        {
                            \Log::error("RemoveLaundryData -> handle => No folder found for order NO".$orderNo);
                        }
                    }
                }
            }
            else
            {
                error_log('Disable Cron');
                return false;
            }
        }
        catch(\Exception $e) 
        {
            \Log::error("RemoveLaundryData -> handle =>".$e->getMessage());
            return false;
        }
    }
}
