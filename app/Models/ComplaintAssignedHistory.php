<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAssignedHistory extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaint_assigned_history';
    protected $fillable = [
        'complaint_id',
        'complaint_priority_id',
        'assigned_to',
        'assigned_by',
        

    ];


    // 🔗 Complaint relationship
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    // 🔗 Priority relationship
    public function priority()
    {
        return $this->belongsTo(ComplaintPriority::class, 'complaint_priority_id');
    }

    // 🔗 Assigned To (User)
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // 🔗 Assigned By (User)
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }


    public function getComplaintAssignedHistory($complaintId=0)
    {
        return ComplaintAssignedHistory::with([
            'complaint:id,complaint_number',
            'priority:id,id,name',
            'assignedTo:id,name',
            'assignedBy:id,name'
        ])->where(['complaint_id' => $complaintId])->get(); //with(['user'])->
        // ->get();
    }
}
