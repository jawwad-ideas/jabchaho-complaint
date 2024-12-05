<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#use  App\Models\OrdersImages;
class OrderHistory extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'item_id',
        'item_image_id',
        'action',
        'admin_user',
        'data'
    ];

    public function createOrderHistory($data = array())
    {
        $inserted = OrderHistory::insert($data);
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
