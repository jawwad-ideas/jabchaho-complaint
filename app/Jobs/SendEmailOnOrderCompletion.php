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
        $emailType = $this->emailType ;
        $emailStatus = $this->sendEmail($this->orderId, $emailType);
   
    }


    public function sendEmail($orderId=0, $emailType )
    {
        try
        {
            $orderData=array();
            if( $emailType == "final_email" )
            {
                $orderData = Order::with([
                    'orderItems' => function ($query) {
                        $query->whereHas('images', function ($query) {
                            $query->where('status', 1);
                        })->withCount([
                            'images as before_wash_count' => function ($query) {
                                $query->where('image_type', 'Before Wash')->where('status', 1);;
                            },
                            'images as after_wash_count' => function ($query) {
                                $query->where('image_type', 'After Wash')->where('status', 1);;
                            }
                        ]);
                    },
                    'orderItems.images' => function ($query) {
                        $query->where('status', 1);
                    }
                ])
                ->withCount(['orderItems as items_count'])
                ->where('id', $orderId)
                ->first();
                
                Mail::send(
                    'backend.emails.orderCompleted',
                    [
                        'orderNo'               => Arr::get($orderData, 'order_id'),
                        'orderToken'            => Arr::get($orderData, 'token'),
                        'name'                  => Arr::get($orderData, 'customer_name'),
                        'app_url'               => URL::to('/'),
                        'orderItems'            => Arr::get($orderData, 'orderItems'),
                        'orderItemCount'        => Arr::get($orderData, 'items_count'),
                    ],
                    function ($message) use ($orderData) {
                        $message->to(trim(Arr::get($orderData, 'customer_email')));
                        $message->from('support@jabchaho.com', 'Jab Chaho Support'); 
                        $message->subject('Your Order Is Ready for Dispatch '. Arr::get($orderData, 'order_id') );
                    }
                );
            }else
            {
                $options = array();
                $optionsString = '';
                $orderData = Order::with(['orderItems' => function ($query) {
                    $query->where('is_issue_identify', 2)
                    ->with(['images' => function ($imageQuery) {
                        $imageQuery->where('image_type', 'Before Wash');
                        $imageQuery->where('status', 1);
                    }]);
                },'orderItems.issues'])
                ->where(['id' =>$orderId ])
                ->first();

                if(!empty(Arr::get($orderData, 'orderItems')))
                {
                    $orderItems = Arr::get($orderData, 'orderItems');

                    foreach($orderItems as $orderItem)
                    {
                        if(!empty(Arr::get($orderItem, 'issues')))
                        {
                            $orderItemIssues = Arr::get($orderItem, 'issues');
                            foreach($orderItemIssues as $orderItemIssue)
                            {
                                if(!empty(Arr::get($orderItemIssue, 'issue')))
                                {
                                    $options[] = config('constants.issues.'.Arr::get($orderItemIssue, 'issue'));//Arr::get($orderItemIssue, 'issue');
                                }
                            }
                        }
                    }
                }

                if(!empty($options))
                {
                    $options = array_unique($options);
                    $optionsString = implode(', ', $options);
                }

                Mail::send(
                    'backend.emails.orderBeforeWash',
                    [
                        'orderNo'               => Arr::get($orderData, 'order_id'),
                        'orderToken'            => Arr::get($orderData, 'token'),
                        'name'                  => Arr::get($orderData, 'customer_name'),
                        'app_url'               => URL::to('/'),
                        'options'               => $optionsString,
                        'orderItems'            => Arr::get($orderData, 'orderItems'),
                    ],
                    function ($message) use ($orderData) {
                        $message->to(trim(Arr::get($orderData, 'customer_email')));
                        $message->from('support@jabchaho.com', 'Jab Chaho Support'); 
                        $message->subject('Product Issues In Order '.Arr::get($orderData, 'order_id') );
                    }
                );
            }

            
            if(!empty($orderData) )
            {
                $orderData->update([ 'updated_at'=>now() ]);
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
