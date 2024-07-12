<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'charges';

    public function newAreaGrids()
    {
        return $this->hasMany(NewAreaGrid::class, 'charge_id');
    }

    public function deleteCharge($chargeId)
    {
        $deleted = Charge::where(['id'=>$chargeId])->delete();
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
