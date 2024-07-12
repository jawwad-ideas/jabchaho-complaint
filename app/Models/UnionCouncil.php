<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnionCouncil extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'union_councils';

    public function deleteUnionCouncil($unionCouncilId)
    {
        $deleted = UnionCouncil::where(['id'=>$unionCouncilId])->delete();
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
