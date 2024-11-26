<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#use  App\Models\OrdersImages;
class OrderItemImage extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_item_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'imagename',
        'admin_user',
        'updated_at',
        'status',
        'image_type'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'item_id');
    }

    public function createOrderItemImage($data = array())
    {
        $inserted = OrderItemImage::insertGetId($data);
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
