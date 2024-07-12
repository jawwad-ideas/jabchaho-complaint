<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewArea extends Model
{
    use HasFactory;
    use SoftDeletes;

    //relation b/w NewArea and Complaint
    public function complaints(){
        return $this->hasMany(Complaint::class,'new_area_id','id');
    }
    //relation b/w NewArea and Complaint
    public function newAreaGrids(){
        return $this->hasMany(NewAreaGrid::class,'new_area_id','id');
    }

    public function deleteNewArea($newAreaId)
    {
        $deleted = NewArea::where(['id'=>$newAreaId])->delete();
        if($deleted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
