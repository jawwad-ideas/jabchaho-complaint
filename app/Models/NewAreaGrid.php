<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewAreaGrid extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'new_area_grids';

    protected $fillable = [
        'new_area_id',
        'district_id',
        'national_assembly_id',
        'provincial_assembly_id',
        'created_by'
    ];

    //relationship b/w NewAreaGrids & complaints
    public function complaints(){
        return $this->hasMany(Complaint::class,'new_area_id','new_area_id');
    }
    //relationship b/w NewAreaGrids & newArea
    public function newArea()
    {
        return $this->belongsTo(NewArea::class, 'new_area_id');
    }
    //relationship b/w NewAreaGrids & charge
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
    //relationship b/w NewAreaGrids & district
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    //relationship b/w NewAreaGrids & subDivision
    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class, 'sub_division_id');
    }
    //relationship b/w NewAreaGrids & unionCouncil
    public function unionCouncil()
    {
        return $this->belongsTo(UnionCouncil::class, 'union_council_id');
    }
    //relationship b/w NewAreaGrids & ward
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
    //relationship b/w NewAreaGrids & nationalAssembly
    public function nationalAssembly()
    {
        return $this->belongsTo(NationalAssembly::class, 'national_assembly_id');
    }
    //relationship b/w NewAreaGrids & provincialAssembly
    public function provincialAssembly()
    {
        return $this->belongsTo(ProvincialAssembly::class, 'provincial_assembly_id');
    }
    //relationship b/w NewAreaGrids & createdBy
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    //relationship b/w NewAreaGrids & updatedBy
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleteNewAreaGrid($newAreaGridId)
    {
        $deleted = NewAreaGrid::where(['id'=>$newAreaGridId])->delete();
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
