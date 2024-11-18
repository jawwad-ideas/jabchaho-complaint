<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use  App\Models\OrdersImages;
class Orders extends Model
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
        'order_number',
        'status',
        'adminuser',
        'updated_at'
    ];



    public function createOrder($data = array())
    {
        $inserted = Orders::insertGetId($data);
        if($inserted)
        {
            return $inserted;
        }
        else
        {
            return false;
        }
    }


    public function images() {
        return $this->hasMany(OrdersImages::class, 'order_id', 'id');
    }
}
