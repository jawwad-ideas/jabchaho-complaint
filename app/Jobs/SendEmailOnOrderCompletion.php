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
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderData=array();
        $orderitemData=array();

        $orderData = Order::where(['id' =>$this->orderId])->first();

        $emailStatus = $this->sendEmail($orderData,$orderitemData);
        if( $emailStatus ){
            $orderData->update([ 'final_email' => 1 , 'updated_at'=>now() ]);
        }
    }


    public function sendEmail($orderData=array(), $orderitemData=array())
    {
        try
        {
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
                    $message->subject('Your Order Is Ready for Dispatch');
                }
            );

            //\Log::info('Email sent successfully to ' . Arr::get($complaintData, 'email'));
            return true;

        } catch (\Exception $e)
        {
            \Log::error('Failed to send email: ' . $e->getMessage());
            return false;
        }

    }
}
