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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function images()
    {
        return $this->hasManyThrough(
            OrderItemImage::class, // Final table
            OrderItem::class,      // Intermediate table
            'order_id',            // Foreign key on OrderItem table
            'item_id',             // Foreign key on OrderItemImage table
            'id',                  // Local key on Orders table
            'id'                   // Local key on OrderItem table
        );
    }

    public function before()
    {
        return $this->images()->where(['image_type'=> 'Before Wash', 'order_item_images.status' =>1]);
    }

    public function after()
    {
        return $this->images()->where(['image_type'=> 'After Wash', 'order_item_images.status' => 1] );
    }



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
