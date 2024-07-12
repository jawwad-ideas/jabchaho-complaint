<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalAssembly extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'national_assemblies';

    public function newAreaGrids()
    {
        return $this->hasMany(NewAreaGrid::class, 'national_assembly_id');
    }

    public function deleteNationalAssembly($nAId)
    {
        $deleted = NationalAssembly::where(['id'=>$nAId])->delete();
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
