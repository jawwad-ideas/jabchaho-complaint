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
use App\Traits\Configuration\ConfigurationTrait;
use App\Helpers\Helper;

class SendWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,ConfigurationTrait;

    /**
     * Create a new job instance.
     */
    protected $orderId;
    protected $params;
    public $tries = 5;
    public $backoff = 60;
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendWhatsApp($this->params);
    }

    public function sendWhatsApp($params=array())
    {
        try {
            $orderId        = Arr::get($params, 'orderId'); 
            $orderNumber    = Arr::get($params, 'orderNumber'); 
            $whatsAppType   = Arr::get($params, 'whatsAppType');

            if($whatsAppType == 'before_whatsapp')
            {
                

                $params['directoryPath']    = url("assets/uploads/orders/{$orderNumber}/pdf/before");
                $params['fileName']         = "JabChaho-Pre-Wash-".$orderNumber.'-'.time().'.pdf';
                $params['title']            = 'Your Pre-wash order number is '.$orderNumber.'. The product report is attached to this message.';
                $params['templateName']     = 'order_report_created';

                $this->generateBeforeWashPDF($params);
                $this->callWhatsAppApi($params);
            }
            else
            {
                $params['directoryPath']    = url("assets/uploads/orders/{$orderNumber}/pdf/after");
                $params['fileName']         = "Jabchaho-Post-Processing-".$orderNumber.'-'.time().'.pdf';
                $params['title']            = 'Your Post-processing order number is '.$orderNumber.'. The product report is attached to this message.';
                $params['templateName']     = 'order_processing_report';

                $this->generateAfterWashPDF($params);
                $this->callWhatsAppApi($params);
            }
            
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return false;
        }
    
    }

    public function callWhatsAppApi($params=array())
    {
        try{

            //configuration filters
            $filters            = ['laundry_order_whatsapp_api_enable','laundry_order_whatsapp_api_url','laundry_order_whatsapp_api_token'];
            
            //get configurations
            $configurations     = $this->getConfigurations($filters);

            //Check cron is enabled or not
            if(!empty(Arr::get($configurations, 'laundry_order_whatsapp_api_enable')))
            {
                $order          = Arr::get($params, 'order'); 
                $directoryPath  = Arr::get($params, 'directoryPath');
                $orderNumber    = Arr::get($params, 'orderNumber'); 
                $fileName       = Arr::get($params, 'fileName'); 
                $title          = Arr::get($params, 'title'); 
                $number         = Arr::get($order, 'telephone'); 
                $postURL        = Arr::get($configurations, 'laundry_order_whatsapp_api_url');
                $apiToken       = Arr::get($configurations, 'laundry_order_whatsapp_api_token');
                $mediaUrl       = $directoryPath.'/'.$fileName;
                $templateName   = Arr::get($params, 'templateName'); 
                ///$fileName       ='jabchaho-before-wash-101625.pdf';
                //$mediaUrl       ='https://complaint.jabchaho.com/assets/uploads/orders/101882/before/pdf/jabchaho-before-wash-101882.pdf';
                //$mediaUrl = 'https://eoceanwaba.com:3050/uploads/platform/builder/support/Playbook.pdf';
                $headers = [
                    'Content-Type' => 'application/json',
                    'Token' =>$apiToken,
                ];
        
                $input = [
                    "phone_number" => $number,
                    "type" => "template",
                    "parameters" => [
                        "name" => $templateName,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "document",
                                        "document" => [
                                            "link" => $mediaUrl,
                                            "filename" => $fileName
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "type" => "body",
                                "parameters" => [
                                    [
                                        "type" => "text",
                                        "text" => $orderNumber
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                //\Log::error('input' .print_r($input,true));

                $params 						= array(); 	
                $params['apiUrl']     			= $postURL;
                $params['input']     			= $input;
                $params['headerParams']         = $headers;
                $params['httpMethod']     		= config('constants.http_methods.post');
                $params['apiType']     			= config('constants.content_type.json');

                #call api
                $axApiResponseDecode = Helper::sendRequestToGateway($params);

                
                return true;

             
            }
            else{
                \Log::error('Disable WhatsApp Api');
                return false;
            }

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return false;
        }
    }


    public function generateBeforeWashPDF($params=array())
    {
        
        try 
        {
            $orderId        = Arr::get($params, 'orderId'); 
            $orderNumber    = Arr::get($params, 'orderNumber'); 
            $fileName       = Arr::get($params, 'fileName'); 
            
            $directoryPath = public_path("assets/uploads/orders/{$orderNumber}/pdf/before");

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
            PDF::setOptions(['isRemoteEnabled' => true]);
            $pdf = Pdf::loadHTML($html);
            
            // Specify the file name and path
            $path = $directoryPath . '/' . $fileName;

            // Save the PDF file
            $pdf->save($path);

            return $orderData;
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error beforeWash PDF: ' . $e->getMessage());
            return false;
        }

    }


    public function generateAfterWashPDF($params=array())
    {
        try 
        {
            $orderId        = $params['orderId']; 
            $orderNumber    = $params['orderNumber']; 
            $fileName       = Arr::get($params, 'fileName'); 
            
            $directoryPath = public_path("assets/uploads/orders/{$orderNumber}/pdf/after");

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
            
            // Specify the file name and path
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
