<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#use  App\Models\OrdersImages;
class Order extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'order_id',
        'status',
        'updated_at',
        'is_email_sent',
        'order_type_id',
        'remarks',
        'attachments'
    ];



    public function createOrder($data = array())
    {
        $inserted = Order::insertGetId($data);
        if($inserted)
        {
            return $inserted;
        }
        else
        {
            return false;
        }
    }


    /*public function images() {
        return $this->hasMany(OrdersImages::class, 'order_id', 'id');
    }*/
}
