<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory;
    use SoftDeletes;

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name'
    ];

    //realtionship b/w District & complaints
    public function complaints(){
        return $this->hasMany(Complaint::class,'district_id','id');
    }

    //realtionship b/w District & newAreaGrids
    public function newAreaGrids()
    {
        return $this->hasMany(NewAreaGrid::class, 'district_id');
    }

    function getDistricts()
    {
        return District::all()->toArray();
    }

    public function deleteDistrict($districtId)
    {
        $deleted = District::where(['id'=>$districtId])->delete();
        if($deleted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
