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
        'device_type',
        'complaint_number',
        'complaint_status_id',
        'complaint_priority_id',
        'complaint_type',
        'order_id',
        'service_id',
        'name',
        'mobile_number',
        'email',
        'comments',
        'user_id',
    ];

     //relationship b/w Complaint & ComplaintStatus
     public function service()
     {
        return $this->belongsTo(Service::class);
     }

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
                //$name = str_replace(' ', '', Arr::get($row, 'name'));
                $name = Arr::get($row, 'name');
                $count = Arr::get($row, 'complaints_count');
                $statusCount[$id] = [
                    'name' => $name,
                    'count' => $count
                ];
            }
        }

        return $statusCount;
    }

    public function getComplaintByUserReport($request=null,$complaintStatuses)
    {
        // Get all status names
        $statusNames = array();
        foreach($complaintStatuses as $complaintStatus)
        {
            $statusNames[] = Arr::get($complaintStatus, 'name');
        }
            
        // Build dynamic query with case statements
        $query = Complaint::query()
        ->join('complaint_statuses as cs', 'complaints.complaint_status_id', '=', 'cs.id')
        ->leftJoin('users as u', 'complaints.user_id', '=', 'u.id')
        ->select('u.name as user_name');

        $query->addSelect(DB::raw('COUNT(*) as total_complaints'),);
        
        foreach ($statusNames as $status) 
        {
            $query->addSelect(DB::raw("COUNT(CASE WHEN cs.name = '$status' THEN 1 END) AS `{$status}_count` "));
        }
        
        ////////////////////Start Apply Filter \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        //name
        if (!empty($request->input('name'))) {
            $query->where('u.name', 'like', '%' . $request->input('name') . '%');
        }

        // Start date time
        if (!empty($request->input('start_date'))) {
            $query->where('complaints.created_at', '>=', $request->input('start_date'));
        }

        //End date time
        if (!empty($request->input('end_date'))) {
            $query->where('complaints.created_at', '<=', $request->input('end_date'));
        }

        //statuses
        if(!empty($request->input('complaint_status_id')))
        {
            if (is_array($request->input('complaint_status_id'))) 
            {
                $query->whereIn('complaints.complaint_status_id', $request->input('complaint_status_id'));
            } else 
            {
                $explodedArray = explode(",", $request->input('complaint_status_id'));
                $query->whereIn('complaints.complaint_status_id', $explodedArray);
            }
        }

        ////////////////////End Apply Filter \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        $query->groupBy('u.name');
        $reportData = $query->get();

        // // Calculate totals
        $totals = array_combine(
            array_map(function ($status) {
                return $status . '_count';
            }, $statusNames),
            array_map(function ($status) use ($reportData) {
                return $reportData->sum($status . '_count');
            }, $statusNames)
        );

        return ['reportData' => $reportData, 'statusNames' => $statusNames,'totals' => $totals];
             
    }

    public function getComplaintCountByService($params = array())
    {
        $complaintCountByService  = array();
        $query = Complaint::
            join('services', 'complaints.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(*) as count'), 'services.icon')
            ->groupBy('complaints.service_id');
        
        // Apply date filtering if startDate and endDate are provided
        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->get();
        
        if(!empty($results))
        {
            foreach($results as $result )
            {
                $icon  = Arr::get($result,'icon');
                if(!empty($icon))
                {
                    $icon = asset('assets/uploads/services').'/'.$icon;
                }
                
                $row['name']        = Arr::get($result,'name');
                $row['count']       = Arr::get($result,'count');
                $row['image']        = $icon;

                $complaintCountByService[] = $row;
            }
        }

        return $complaintCountByService;
    } 

}