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
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use App\Helpers\Helper;
use App\Http\Traits\Configuration\ConfigurationTrait;

class ComplaintStatusChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ConfigurationTrait;

    /**
     * Create a new job instance.
     */
    protected $complaintId;
    protected $complaintStatusId;
    protected $configurations;

    public function __construct($complaintId,$complaintStatusId,$configurations)
    {
        $this->complaintId              = $complaintId;
        $this->complaintStatusId        = $complaintStatusId;
        $this->configurations        = $configurations;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            $complaintObject            = new Complaint;
            $objectComplaintStatus      = new ComplaintStatus;
            $complaintData              = $complaintObject->getComplaintDataById($this->complaintId);
            $complaintStatusData        = $objectComplaintStatus->getComplaintStatusById($this->complaintStatusId);

            
        if(Arr::get($this->configurations, 'complaint_status_notify_type') == config('constants.complaint_status_notify_type_id.email') || Arr::get($this->configurations, 'complaint_status_notify_type') == config('constants.complaint_status_notify_type_id.both'))
        { 
            \Log::info('complaint_status_notify_type=>email or both');
            //Send Email
            $this->sendStatusChangedEmailToComplainant($complaintData,$complaintStatusData);
        }
        
        if(Arr::get($this->configurations, 'complaint_status_notify_type') == config('constants.complaint_status_notify_type_id.sms') || Arr::get($this->configurations, 'complaint_status_notify_type') == config('constants.complaint_status_notify_type_id.both'))
        {    
            \Log::info('complaint_status_notify_type=>sms or both');
            //Send Email
            $this->sendStatusChangedSmsToComplainant($complaintData,$complaintStatusData,$this->configurations);

        }
        
    }


    public function sendStatusChangedEmailToComplainant($complaintData,$complaintStatusData)
    {
        try 
        {
            Mail::send(
                'backend.emails.complaintStatusChanged',
                [
                    'complaintNumber'       => Arr::get($complaintData, 'complaint_number'),
                    'name'                  => Arr::get($complaintData, 'name'),
                    'app_url'               => URL::to('/'),
                    'newStatus'             => Arr::get($complaintStatusData, 'name'),
                ],
                function ($message) use ($complaintData) {
                    $message->to(trim(Arr::get($complaintData, 'email')));
                    $message->subject(' Complaint Status Update');
                }
            );

            return true;
            
        } catch (\Exception $e) 
        {
            \Log::error('Failed to send email: ComplaintStatusChanged->' . $e->getMessage());
            return false;
        }
    }

    public function sendStatusChangedSmsToComplainant($complaintData=array(),$complaintStatusData=array(),$configurations=array())
    {
        try 
        {
            //Check sms api is enabled or not
            if(!empty(Arr::get($configurations, 'complaint_sms_api_enable')))
            {

                $data = [
                    'complaint_number' => Arr::get($complaintData, 'complaint_number'),
                    'name' => Arr::get($complaintStatusData, 'name'),
                ];
                
                $message = Helper::replaceSmsTemplate(Arr::get($configurations, 'complaint_status_changed_sms_template'),$data);

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

    
}
