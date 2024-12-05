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

    function orderItemImagesCount($params = array())
    {
        $query = OrderItemImage::selectRaw('
            SUM(CASE WHEN image_type = "Before Wash" THEN 1 ELSE 0 END) as before_wash,
            SUM(CASE WHEN image_type = "After Wash" THEN 1 ELSE 0 END) as after_wash
        ');

        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $result = $query->first();
        $data = [
            'before_wash' => (int) $result->before_wash,
            'after_wash' => (int) $result->after_wash,
        ];

        return $data;
    }

}
