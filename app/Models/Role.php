<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    //relationship b/w Role & ModelHasRole
    function model_has_role(){
        $this->belongsTo('model_has_role','role_id');
    }
}
