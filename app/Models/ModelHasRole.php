<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;

    protected $table = 'model_has_roles';

    //relationship b/w ModelHasRole & roleAssigned
    public function roleAssigned(){
        return $this->hasOne(Role::class,'id','role_id');
    }
    //relationship b/w ModelHasRole & User
    public function user(){
        return $this->belongsTo(User::class, 'id');
    }
}
