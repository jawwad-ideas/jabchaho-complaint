<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'department_id',
        'new_area_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array

     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array

     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    //relationship b/w user & complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id', 'id');
    }

   
    //relationship b/w user & userRolesAssigned
    public function userRolesAssigned()
    {
        return $this->hasMany(ModelHasRole::class, 'model_id', 'id');
    }
    
    //get all users with role id = ?
    function getUsersWithRole($roleID = 0)
    {
        $users = User::select('users.id', 'users.name', 'model_has_roles.role_id as role_id')
                ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id',$roleID)
                ->get();
        return $users;
    }

    function getUserById($userId=0)
    {
        return User::where(['id' => $userId])->first();
    }

}
