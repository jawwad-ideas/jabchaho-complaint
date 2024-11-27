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

        $this->sendEmail($orderData,$orderitemData);
    }


    public function sendEmail($orderData=array(), $orderitemData=array())
    {
        try 
        {
            Mail::send(
                'backend.emails.orderCompleted',
                [
                    'orderNo'               => Arr::get($orderData, 'order_id'),
                    'name'                  => Arr::get($orderData, 'name'),
                    'app_url'               => URL::to('/'),
                ],
                function ($message) use ($orderData) {
                    $message->to(trim(Arr::get($orderData, 'email')));
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
