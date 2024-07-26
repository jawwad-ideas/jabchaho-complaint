<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Complaint extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_number',
        'complaint_status_id',
        'complaint_priority_id',
        'complaint_type',
        'order_id',
        'name',
        'mobile_number',
        'email',
        'comments',
        'user_id',
    ];

    //relationship b/w Complaint & ComplaintStatus
    public function complaintStatus()
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id');
    }
    //relationship b/w Complaint & complaintPriority
    public function complaintPriority()
    {
        return $this->belongsTo(ComplaintPriority::class, 'complaint_priority_id');
    }
    //relationship b/w Complaint & User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //relationship b/w Complaint & created_by
    public function userBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //relationship b/w Complaint & ComplaintDocument
    public function complaintDocument()
    {
        return $this->belongsTo(ComplaintDocument::class, 'id', 'complaint_id');
    }
    

    //relationship b/w Complaint & FollowUp
    public function ComplaintFollowUps()
    {
        return $this->belongsTo(ComplaintFollowUp::class, 'id','complaint_id');
    }

    public function deleteComplaint($complaintId)
    {
        // Find the Complaint model instance based on the ID
        $complaint = Complaint::select('*')->where(['id' => $complaintId]);

        // Check if the complaint exists
        if ($complaint) {
            // Soft delete the complaint
            $complaint->delete();

            return "Complaint deleted successfully.";
        } else {
            return "Complaint not found.";
        }
    }

    public function getComplaintDataById($complaintId)
    {
        $complaintData = Complaint::where(['id' => $complaintId])->first();
        return $complaintData;
    }

    public function assignTo($params = array())
    {
        $complaintId    = Arr::get($params, 'complaintId');
        $userId         = Arr::get($params, 'userId');
        $priorityId     = Arr::get($params, 'priorityId');

        $assigned = Complaint::where(['id'=>$complaintId])->update(['user_id'=>$userId, 'complaint_priority_id'=>$priorityId]);
        if ($assigned) {
            return true;
        } else {
            return false;
        }
    }

    function complaintCount($params = array())
    {
        $query = Complaint::select('*');
        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $result  = $query->count(); 
        return $result;
    }

    function complaintStatusCount($params = array())
    {
        $wherecondition = '';
        $complaintStatusesWithCounts = ComplaintStatus::select('id','name')->withCount([
            'complaints' => function ($query) use ($params) {
                if (!empty($params['startDate']) && !empty($params['endDate'])) {
                    $startDate = $params['startDate'];
                    $endDate = $params['endDate'];
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        ])->get();

        $statusCount = array();
        if (!empty($complaintStatusesWithCounts)) {
            foreach ($complaintStatusesWithCounts as $row) 
            {
                $id =  Arr::get($row, 'id');
                $name = str_replace(' ', '', Arr::get($row, 'name'));
                $count = Arr::get($row, 'complaints_count');
                $statusCount[$id] = [
                    'name' => $name,
                    'count' => $count
                ];
            }
        }

        return $statusCount;
    }

    
    public function reAssignTo($params = array())
    {

        $complaintId = Arr::get($params, 'complaintId');
        $mnaId       = Arr::get($params,'mnaId');
        $mpaId       = Arr::get($params,'mpaId');

        $data = [
            'user_id' => $mnaId,
            'mpa_id'  => $mpaId  
        ];
        
        $reAssigned = Complaint::where(['id' => $complaintId])->update($data);
        if ($reAssigned) {
            return true;
        } else {
            return false;
        }
    }

}
