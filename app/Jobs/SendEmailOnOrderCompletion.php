<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\Order;

class SendEmailOnOrderCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $orderId;
    protected $emailType;
    public function __construct($orderId,$emailType)
    {
        $this->orderId = $orderId;
        $this->emailType = $emailType;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderData=array();
        $orderitemData=array();

        $emailType = $this->emailType ;

        $orderData = Order::where(['id' =>$this->orderId ])->first();

        $emailStatus = $this->sendEmail($orderData,$orderitemData , $emailType);
        if( $emailStatus ){
            $orderData->update([ 'updated_at'=>now() ]);
        }
    }


    public function sendEmail($orderData=array(), $orderitemData=array() , $emailType )
    {
        try
        {
            if( $emailType == "final_email" ){
                Mail::send(
                    'backend.emails.orderCompleted',
                    [
                        'orderNo'               => Arr::get($orderData, 'order_id'),
                        'orderToken'            => Arr::get($orderData, 'token'),
                        'name'                  => Arr::get($orderData, 'customer_name'),
                        'app_url'               => URL::to('/'),
                    ],
                    function ($message) use ($orderData) {
                        $message->to(trim(Arr::get($orderData, 'customer_email')));
                        $message->from('support@jabchaho.com', 'Jab Chaho Support'); 
                        $message->subject('Your Order Is Ready for Dispatch '. Arr::get($orderData, 'order_id') );
                    }
                );
            }else{
                Mail::send(
                    'backend.emails.orderBeforeWash',
                    [
                        'orderNo'               => Arr::get($orderData, 'order_id'),
                        'orderToken'            => Arr::get($orderData, 'token'),
                        'name'                  => Arr::get($orderData, 'customer_name'),
                        'app_url'               => URL::to('/'),
                        'remarks'               => Arr::get($orderData, 'before_email_remarks'),
                        'options'               => Arr::get($orderData, 'before_email_options'),
                    ],
                    function ($message) use ($orderData) {
                        $message->to(trim(Arr::get($orderData, 'customer_email')));
                        $message->from('support@jabchaho.com', 'Jab Chaho Support'); 
                        $message->subject('Product Issues In Order '.Arr::get($orderData, 'order_id') );
                    }
                );
            }



            //\Log::info('Email sent successfully to ' . Arr::get($complaintData, 'email'));
            return true;

        } catch (\Exception $e)
        {
            \Log::error('Failed to send email: ' . $e->getMessage());
            return false;
        }

    }
}
