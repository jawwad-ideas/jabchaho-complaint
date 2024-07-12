<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDivision extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sub_divisions';

    public function deleteSubDivision($subDivisionId)
    {
        $deleted = SubDivision::where(['id'=>$subDivisionId])->delete();
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
