<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#use  App\Models\OrdersImages;
class Customer extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_email',
        'customer_name',
        'telephone',
        'status',
        'updated_at'
    ];


    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }



    public function createCustomer($data = array())
    {
        $inserted = Customer::insertGetId($data);
        if($inserted)
        {
            return $inserted;
        }
        else
        {
            return false;
        }
    }

}
