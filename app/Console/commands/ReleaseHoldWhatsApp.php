<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Arr;
use App\Jobs\SendWhatsAppJob as SendWhatsAppJob;
use App\Models\OrderHistory;
use App\Traits\QueueWorker\QueueWorkerTrait;
use App\Traits\Orders\WhatsApp\Hold\ProcessHoldOrdersTrait;
use App\Traits\Configuration\ConfigurationTrait as ConfigurationTrait;

class ReleaseHoldWhatsApp extends Command
{
    use QueueWorkerTrait,ProcessHoldOrdersTrait,ConfigurationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'release:hold-whatsapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'release Hold WhatsApp orders when before_whatsapp or after_whatsapp is 3';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        try
        {
            //configuration filters
            $filters            = ['laundry_order_release_hold_whatsapp_cron_enable'];
            
            //get configurations
            $configurations     = $this->getConfigurations($filters);
        
            //Check cron is enabled or not
            if(!empty($configurations['laundry_order_release_hold_whatsapp_cron_enable']))
            {  
            
                $data = "This task is executed via the ReleaseHoldWhatsApp cron command.";
                // Fetch orders with before_whatsapp = 3
                $beforeOrders = Order::where('before_whatsapp', 3)->get();

                if($beforeOrders->isNotEmpty())
                {
                    foreach ($beforeOrders as $order) 
                    {
                        $this->processAndReleaseHoldOrders($order, 'before_whatsapp',$data);
                    }
                }
                

                // Fetch orders with after_whatsapp = 3
                $afterOrders = Order::where('after_whatsapp', 3)->get();
                
                if($afterOrders->isNotEmpty())
                {
                    foreach ($afterOrders as $order) {
                        $this->processAndReleaseHoldOrders($order, 'after_whatsapp',$data);
                    }
                }
            }
            else
            {
                \Log::error('Disable ReleaseHoldWhatsApp Cron');
                return false;
            }
        }
        catch(\Exception $e) 
        {
            \Log::error("ReleaseHoldWhatsApp -> handle =>".$e->getMessage());
            return false;
        }
    }

}
