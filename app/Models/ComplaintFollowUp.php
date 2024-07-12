<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintFollowUp extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaint_follow_up';

    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [
        'complaint_id',
        'complaint_status_id',
        'description',
        'is_notify',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the job_comments associated with the user.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

     /**
     * Get the job_comments associated with the JobStatus.
     */
    public function complaintStatus()
    {
        return $this->hasOne(ComplaintStatus::class, 'id', 'complaint_status_id');
    }

    public function getComplaintFollowUps($complaintId=0)
    {
        return ComplaintFollowUp::with(['user','complaintStatus'])->where(['complaint_id' => $complaintId])->orderBy('id', 'DESC')->paginate(4);
        // ->get();
    }

    public function getComplaintIsNotifyFollowUps($complaintId=0)
    {
        return ComplaintFollowUp::with(['user','complaintStatus'])->where(['complaint_id' => $complaintId,'is_notify' => 1])->orderBy('id', 'DESC')->paginate(4); 
        // ->get();
    }

    public function getNotifyFollowUp($followUpId=0)
    {
        return ComplaintFollowUp::with(['complaintStatus'])->where(['id' =>  $followUpId])->orderBy('id', 'DESC')->first();
    }
}