<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

class SendWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $orderId;
    protected $params;
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->generatePDF($this->params);
    }



    public function generatePDF($params=array())
    {
        try {
            $orderId        = $params['orderId']; 
            $orderNumber    = $params['orderNumber']; 
            $whatsAppType   = $params['whatsAppType'];


            if($whatsAppType == 'before_whatsapp')
            {
                return $this->beforeWashPDF($params);
            }
            else
            {
                return $this->afterWashPDF($params);
            }
 
            
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return false;
        }
    
    }


    public function beforeWashPDF($params=array())
    {
        
        try 
        {
            $orderId        = $params['orderId']; 
            $orderNumber    = $params['orderNumber']; 
            
            $directoryPath = public_path("assets/uploads/orders/{$orderNumber}/before/pdf");

            // Ensure the directory exists
            if (!file_exists($directoryPath)) 
            {
                mkdir($directoryPath, 0755, true); // Create directory if it doesn't exist
            }

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

            

            $data = [
                'orderNo'               => Arr::get($orderData, 'order_id'),
                'name'                  => Arr::get($orderData, 'customer_name'),
                'app_url'               => URL::to('/'),
                'options'               => $optionsString,
                'orderItems'            => Arr::get($orderData, 'orderItems'),
            ];

            
            $html = view('backend.pdf.beforeWash', $data)->render();
            $pdf = Pdf::loadHTML($html);
            
            // Load the Blade view for the PDF
            //$pdf = PDF::loadView('backend.pdf.before', $data);

            // Specify the file name and path
            $fileName = "before-".Arr::get($orderData, 'order_id').'.pdf';
            $path = $directoryPath . '/' . $fileName;

            // Save the PDF file
            $pdf->save($path);

            return true;
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error beforeWash PDF: ' . $e->getMessage());
            return false;
        }

    }


    public function afterWashPDF($params=array())
    {
        try 
        {
            $orderId        = $params['orderId']; 
            $orderNumber    = $params['orderNumber']; 
            
            $directoryPath = public_path("assets/uploads/orders/{$orderNumber}/after/pdf");

            // Ensure the directory exists
            if (!file_exists($directoryPath)) 
            {
                mkdir($directoryPath, 0755, true); // Create directory if it doesn't exist
            }

            $orderData=array();
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


            $data = 
            [
                'orderNo'               => Arr::get($orderData, 'order_id'),
                'name'                  => Arr::get($orderData, 'customer_name'),
                'app_url'               => URL::to('/'),
                'orderItems'            => Arr::get($orderData, 'orderItems'),
                'orderItemCount'        => Arr::get($orderData, 'items_count'),
            ];


            $html = view('backend.pdf.aftereWash', $data)->render();
            $pdf = Pdf::loadHTML($html);
            
            // Load the Blade view for the PDF
            //$pdf = PDF::loadView('backend.pdf.before', $data);

            // Specify the file name and path
            $fileName = "after-".Arr::get($orderData, 'order_id').'.pdf';
            $path = $directoryPath . '/' . $fileName;

            // Save the PDF file
            $pdf->save($path);

            return true;
            


        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error afterWashPDF PDF: ' . $e->getMessage());
            return false;
        }
    }
}
