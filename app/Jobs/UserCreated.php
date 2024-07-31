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
use App\Models\User;

class UserCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $userData;
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendEmailToCreatedUser($this->userData);
    }


    public function sendEmailToCreatedUser($userData=array())
    {
        try 
        {
            Mail::send(
                'backend.emails.userCreated',
                [
                    'app_url'               => URL::to('/'),
                    'name'                  => Arr::get($userData, 'name'),
                    'username'              => Arr::get($userData, 'username'),
                    'password'              => Arr::get($userData, 'password'),
                ],
                function ($message) use ($userData) {
                    $message->to(trim(Arr::get($userData, 'email')));
                    $message->subject('Welcome to Jabchaho Complaint Portal!');
                }
            );

            return true;
            
        } catch (\Exception $e) 
        {
            \Log::error('Failed to send email: sendEmailToCreatedUser->' . $e->getMessage());
            return false;
        }
    }
}
