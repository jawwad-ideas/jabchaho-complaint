<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
