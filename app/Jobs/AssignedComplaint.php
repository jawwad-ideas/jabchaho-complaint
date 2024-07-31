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
use App\Models\User;

class AssignedComplaint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $complaintId;
    protected $userId;
    public function __construct($complaintId,$userId)
    {
        $this->complaintId  = $complaintId;
        $this->userId       = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $complaintObject = new Complaint;
        $userObject = new User;
        $complaintData  = $complaintObject->getComplaintDataById($this->complaintId);
        $userData       = $userObject->getUserById($this->userId );

        //Send Email
        $this->sendEmailToUser($complaintData,$userData);
    }

    public function sendEmailToUser($complaintData=array(),$userData=array())
    {
        try 
        {
            Mail::send(
                'backend.emails.assignedComplaint',
                [
                    'complaintNumber'       => Arr::get($complaintData, 'complaint_number'),
                    'priority'              => Arr::get($complaintData->complaintPriority, 'name'), 
                    'name'                  => Arr::get($userData, 'name'),
                    'app_url'               => URL::to('/'),
                ],
                function ($message) use ($userData) {
                    $message->to(trim(Arr::get($userData, 'email')));
                    $message->subject('Complaint Assignment Notification with Priority');
                }
            );

            return true;
            
        } catch (\Exception $e) 
        {
            \Log::error('Failed to send email: sendEmailToUser->' . $e->getMessage());
            return false;
        }
    }
}
