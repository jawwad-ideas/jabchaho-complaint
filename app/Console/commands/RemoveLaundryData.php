<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
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
            //configuration filters
            $filters            = ['laundry_order_cron_enable','laundry_order_delete_days_from_now'];
            
            //get configurations
            $configurations     = $this->getConfigurations($filters);
      
            //Check cron is enabled or not
            if(!empty($configurations['laundry_order_cron_enable']))
            {  
                $numberOfDays = Arr::get($configurations, 'laundry_order_delete_days_from_now');
                $date = date('Y-m-d', strtotime('-'.$numberOfDays.' days'));

                $orders = Order::where('created_at', '<', $date . ' 23:59:59')->get();
            
                // Now you can use $orderNos outside the transaction block
                if(!empty($orders))
                {
                    foreach ($orders as $order) 
                    {
                        $orderNo    = Arr::get($order, 'order_id');
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
