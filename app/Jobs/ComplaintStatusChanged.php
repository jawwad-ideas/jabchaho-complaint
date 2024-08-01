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

class ComplaintStatusChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $complaintId;
    protected $complaintStatusId;

    public function __construct($complaintId,$complaintStatusId)
    {
        $this->complaintId              = $complaintId;
        $this->complaintStatusId        = $complaintStatusId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $complaintObject            = new Complaint;
        $objectComplaintStatus      = new ComplaintStatus;
        $complaintData              = $complaintObject->getComplaintDataById($this->complaintId);
        $complaintStatusData        = $objectComplaintStatus->getComplaintStatusById($this->complaintStatusId );
    
        //Send Email
        $this->sendStatusChangedEmailToComplainant($complaintData,$complaintStatusData);
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
    
}
