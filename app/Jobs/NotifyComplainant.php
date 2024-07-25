<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Http\Traits\Configuration\ConfigurationTrait;
use App\Models\Complaint;

class NotifyComplainant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,ConfigurationTrait;

    /**
     * Create a new job instance.
     */
    protected $complaintId;
    public function __construct($complaintId)
    {
        $this->complaintId = $complaintId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $complaintObject = new Complaint;
        $complaintData = $complaintObject->getComplaintDataById($this->complaintId);
        
        //Send SMS
        $this->sendSmsToComplainant($complaintData);

        //Send Email
        $this->sendEmailToComplainant($complaintData);
    }

    public function sendSmsToComplainant($complaintData=array())
    {
        try 
        {
            //configuration filters
            $filters            = ['complaint_sms_api_enable','complaint_sms_action','complaint_sms_sender','complaint_sms_username','complaint_sms_password','complaint_sms_format','complaint_sms_api_url','complaint_sms_template'];
            
            //get configurations
            $configurations     = $this->getConfigurations($filters);

            //Check sms api is enabled or not
            if(!empty(Arr::get($configurations, 'complaint_sms_api_enable')))
            {

                $data = [
                    'complaint_number' => Arr::get($complaintData, 'complaint_number')
                ];
                
                $message = Helper::replaceSmsTemplate(Arr::get($configurations, 'complaint_sms_template'),$data);

                $input 						    = array(); 
                $input['action']                = Arr::get($configurations, 'complaint_sms_action');
                $input['sender']                = Arr::get($configurations, 'complaint_sms_sender');
                $input['username']              = Arr::get($configurations, 'complaint_sms_username');
                $input['password']              = Arr::get($configurations, 'complaint_sms_password');
                $input['recipient']             = Helper::formatPhoneNumber(Arr::get($complaintData, 'mobile_number'));
                $input['messagedata']           = $message;
                $input['format']                = Arr::get($configurations, 'complaint_sms_format');
                
                $params 						= array(); 	
                $params['apiUrl']     			= Arr::get($configurations, 'complaint_sms_api_url');
                $params['input']     			= $input;
                $params['httpMethod']     		= config('constants.http_methods.get');
                $params['apiType']     			= config('constants.content_type.xml');

                #call api
                $axApiResponseDecode = Helper::sendRequestToGateway($params);
                //\Log::info('SMS sent successfully to ');
                //\Log::info(''.print_r($axApiResponseDecode,true));
                return true;
            }
        } 
        catch (\Exception $e) 
        {
            \Log::error('Failed to send sms: ' . $e->getMessage());
            return false;
        }
    }

    public function sendEmailToComplainant($complaintData=array())
    {
        try 
        {
            Mail::send(
                'backend.emails.complaintGenerated',
                [
                    'complaintNumber'       => Arr::get($complaintData, 'complaint_number'),
                    'orderId'               => Arr::get($complaintData, 'order_id'),
                    'queryType'             => config('constants.query_type.'.Arr::get($complaintData, 'query_type')),
                    'complaintType'         => config('constants.complaint_type.'.Arr::get($complaintData, 'complaint_type')),
                    'inquiryType'           => config('constants.inquiry_type.'.Arr::get($complaintData, 'inquiry_type')),
                    'name'                  => Arr::get($complaintData, 'name'),
                    'email'                 => Arr::get($complaintData, 'email'),
                    'mobileNumber'          => Arr::get($complaintData, 'mobile_number'),
                    'additionalComments'    => Arr::get($complaintData, 'comments'),
                    'app_url'               => URL::to('/'),
                ],
                function ($message) use ($complaintData) {
                    $message->to(trim(Arr::get($complaintData, 'email')));
                    $message->subject('Complaint Registered Successfully');
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
