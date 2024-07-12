<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProvincialAssembly extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $table = 'provincial_assemblies';

    public function deleteProvincialAssembly($pAId)
    {
        $deleted = ProvincialAssembly::where(['id'=>$pAId])->delete();
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
