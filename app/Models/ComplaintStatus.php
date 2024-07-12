<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintStatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaint_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'is_enabled',
        'created_at',
        'updated_at',

    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'complaint_status_id', 'id');
    }


    function getComplaintStatuses()
    {
        return ComplaintStatus::where(['is_enabled' => 1])->get(['id','name']);
    }

    public function deleteComplaintStatus($complaintStatusId)
    {
        $deleted = ComplaintStatus::where(['id'=>$complaintStatusId])->delete();
        if($deleted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function createComplaintStatus($data=array())
    {
        $inserted = ComplaintStatus::insert($data);
        if($inserted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateComplaintStatus($data=array(),$complaintStatusId)
    {
        $updated = ComplaintStatus::where(['id'=>$complaintStatusId])->update($data);

        if($updated)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
