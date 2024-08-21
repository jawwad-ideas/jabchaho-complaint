<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Review extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_type',
        'status',
        'name',
        'email',
        'mobile_number',
        'order_id',
        'pricing_value',
        'service_quality',
        'timelines_convenience',
        'comments',
    ];

    //get review by status
    public function getReviewsByStatus($status=0)
    {
        return Review::select('id','status','name','email','mobile_number','order_id', 
        DB::raw('IFNULL(pricing_value, 0) as pricing_value'),
        DB::raw('IFNULL(service_quality, 0) as service_quality'),
        DB::raw('IFNULL(timelines_convenience, 0) as timelines_convenience'),
        'comments')->where(['status' => $status])->get();
    }
}
