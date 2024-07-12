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

    //relationship b/w user & complaints
    public function complaintsMpa()
    {
        return $this->hasMany(Complaint::class, 'mpa_id', 'id');
    }
    //relationship b/w user & areasAssigned
    public function areasAssigned()
    {
        return $this->hasMany(UserWiseAreaMapping::class, 'user_id', 'id');
    }
    //relationship b/w user & userRolesAssigned
    public function userRolesAssigned()
    {
        return $this->hasMany(ModelHasRole::class, 'model_id', 'id');
    }
    //get user wise area mapping na,ps,area name
    public function getAreaNaPsName($id)
    {
        $user_wise_area_mappings = new UserWiseAreaMapping;
        $result = $user_wise_area_mappings->select('new_areas.name as area_name','national_assemblies.name as na_name','provincial_assemblies.name as ps_name')
        ->Join('new_areas','new_areas.id','user_wise_area_mappings.new_area_id')
        ->Join('national_assemblies','national_assemblies.id','user_wise_area_mappings.national_assembly_id')
        ->Join('provincial_assemblies','provincial_assemblies.id','user_wise_area_mappings.provincial_assembly_id')
        ->where('user_id',$id)
        ->first();
        return $result;
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

    function getMnaWiseMpa($mnaId, $provincialAssemblyId, $nationalAssemblyId)
    {
        $results = DB::table('user_wise_area_mappings as usps')
            ->join('model_has_roles as mds', 'mds.model_id', '=', 'usps.user_id')
            ->join('roles as rs', 'rs.id', '=', 'mds.role_id')
            ->join('users', 'users.id', '=', 'usps.user_id')
            ->where('usps.national_assembly_id', '=', $nationalAssemblyId)
            ->where('usps.provincial_assembly_id', '=', $provincialAssemblyId)
            ->where('usps.user_id', '!=', $mnaId)
            ->select('usps.user_id', 'users.name as user_name', 'mds.role_id', 'usps.provincial_assembly_id', 'usps.national_assembly_id')
            ->groupBy('usps.user_id', 'users.name', 'mds.role_id', 'usps.provincial_assembly_id', 'usps.national_assembly_id')
            ->get();

        return $results;
    }
}
