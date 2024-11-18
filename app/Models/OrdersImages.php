<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Orders;
class OrdersImages extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'image_type',
        'image_path',
        'filename',
        'status',
        'adminuser',
        'updated_at'
    ];

    public function orders() {
        return $this->belongsTo(Orders::class);
    }

    public function createOrderImage($data = array())
    {
        $inserted = OrdersImages::insert($data);
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
