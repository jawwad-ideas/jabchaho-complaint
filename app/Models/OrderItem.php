<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#use  App\Models\OrdersImages;
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'service_type',
        'barcode',
        'item_name',
        'updated_at',
        'status',
        'qty'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function images()
    {
        return $this->hasMany(OrderItemImage::class, 'item_id');
    }



    public function createOrderItem($data = array())
    {
        $inserted = OrderItem::insertGetId($data);
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
