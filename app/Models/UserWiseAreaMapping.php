<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWiseAreaMapping extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_wise_area_mappings';
    protected $fillable = [
        'user_id',
        'district_id',
        'new_area_id',
        'national_assembly_id',
        'provincial_assembly_id'
    ];

    //relationship b/w UserWiseAreaMapping & user
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function newArea(){
        return $this->belongsTo(NewArea::class, 'new_area_id');
    }
    public function nationalAssembly(){
        return $this->belongsTo(NationalAssembly::class, 'national_assembly_id');
    }

    public function district(){
        return $this->belongsTo(District::class, 'district_id');
    }


    public function provincialAssembly(){
        return $this->belongsTo(ProvincialAssembly::class, 'provincial_assembly_id');
    }
    //relationship b/w UserWiseAreaMapping & complaints
    public function complaints(){
        return $this->hasMany(Complaint::class,'new_area_id','new_area_id');
    }
    public function deleteAreaMapping($area_id)
    {
        $deleted = UserWiseAreaMapping::where(['user_id'=>$area_id])->delete();
        if($deleted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getAreas($userId)
    {
        $areasArray = '';
        $count = 0;
        $areas = UserWiseAreaMapping::select('new_areas.name')
        ->join('new_areas','new_areas.id','=','user_wise_area_mappings.new_area_id')
        ->where(['user_wise_area_mappings.user_id'=> $userId])->get();
        
        if(!empty($areas)){
            foreach ($areas as $area) {
                $count++;
                $areasArray .= "<span class='badge bg-theme-green'>".$area->name. "</span> ";
                if($count >= 2){
                    $areasArray.= "<br/>";
                    $count = 0;
                }
            }
        }

        return $areasArray;
    }
    public function getAreasIds($userId)
    {
        $areasArray = [];
        $areas = UserWiseAreaMapping::select('new_areas.id')
        ->join('new_areas','new_areas.id','=','user_wise_area_mappings.new_area_id')
        ->where(['user_wise_area_mappings.user_id'=> $userId])->get();
        
        if(!empty($areas)){
            foreach ($areas as $area) {
                $areasArray[] = $area->id;
            }
        }

        return $areasArray;
    }
}
