<?php

namespace App\Models;

use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Complainant extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complainants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'cnic',
        'mobile_number',
        'gender',
        'created_at',
        'updated_at'

    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        //'remember_token',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
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

    //relationship b/w Complainant & complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'complainant_id', 'id')->whereNull('deleted_at');
    }

    public static function findByEmailOrCnicOrPhoneNo(string $email, string $cnic,string $phoneNumber): Complainant|null
    {
        return self::where(function ($query) use ($email, $cnic,$phoneNumber) {
            $query->where('email', $email)
                ->orWhere('cnic', $cnic)
                ->orWhere('mobile_number', $phoneNumber);
        })->first();
    }

    public static function getUniqueNicNumbers(): array
    {
        return self::select('*')->distinct('cnic')->get()->toArray();
    }

    public static function getUniquePhoneNumbers(): array
    {
        return self::select('*')->distinct('mobile_number')->get()->toArray();
    }

    public function deleteComplainant($complainantId)
    {
        $complainant = Complainant::select('*')->where(['id' => $complainantId]);

        // Check if the complainant exists
        if ($complainant) {
            // Soft delete the complainant
            $complainant->delete();

            return "Complainant deleted successfully.";
        } else {
            return "Complainant not found.";
        }
    }
    public static function validateComplainant($validateValues)
    {
        $cnic = $validateValues['cnic'];
        $mobile_number = $validateValues['mobile_number'];
        $email = $validateValues['email'];
        $exists = Complainant::where(function ($query) use ($cnic, $mobile_number, $email) {
            $query->where('cnic', $cnic)
                ->orWhere('mobile_number', $mobile_number)
                ->orWhere('email', $email);
        })->exists();

        return $exists;
    }

}
