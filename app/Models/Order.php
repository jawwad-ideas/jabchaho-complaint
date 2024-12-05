<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

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
        'final_email',
        'order_type_id',
        'remarks',
        'attachments',
        'before_email',
        'before_email_remarks',
        'before_email_options',
        'token'
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


    function orderCount($params = array())
    {
        $query = Order::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as process,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed
        ');

        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $result = $query->first();
        $data = [
            'total' => (int) $result->total,
            'process' => (int) $result->process,
            'completed' => (int) $result->completed,
        ];
        
        return $data;
    }

    function filterOrdersWithItemCount($params=array(),$query =null)
    {
        $customerName   = Arr::get($params, 'name');
        $telephone      = Arr::get($params, 'telephone');
        $orderNumber    = Arr::get($params, 'orderNumber');
        $startDate      = Arr::get($params, 'startDate');
        $endDate        = Arr::get($params, 'endDate');
        $page           = Arr::get($params, 'page');
        $limit          = Arr::get($params, 'limit');

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        // Add `where` conditions if filters are provided
        if (!empty($customerName)) 
        {
            $query->where('orders.customer_name', 'LIKE', "%{$customerName}%");
        }

        if (!empty($telephone)) 
        {
            $query->where('orders.telephone', 'LIKE',  '%'.$telephone.'%' );
        }

        if (!empty($orderNumber)) 
        {
            $query->where('orders.order_id', $orderNumber);
        }

        return $query;
    }



    function getOrdersWithItemCount($params=array())
    {
        $customerName   = Arr::get($params, 'name');
        $telephone      = Arr::get($params, 'telephone');
        $orderNumber    = Arr::get($params, 'orderNumber');
        $startDate      = Arr::get($params, 'startDate');
        $endDate        = Arr::get($params, 'endDate');
        $page           = Arr::get($params, 'page');
        $limit          = Arr::get($params, 'limit');
        
        $orders = Null;
        if($customerName || $telephone || $orderNumber)
        {
            
            $query = Order::select(
                'orders.id',
                'orders.order_id as order_id',
                DB::raw('COUNT(order_items.id) as item_count')
            )
            ->leftjoin('order_items', 'orders.id', '=', 'order_items.order_id') // Join orders with order_items
            ->groupBy('orders.id')// Group by orders.id to calculate the item count
            ->orderBy('orders.created_at', 'desc');
    
               
           $queryWithFilter =  $this->filterOrdersWithItemCount($params,$query);
    
            // Apply pagination
            $orders = $queryWithFilter
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

            
            #########################################count query #####################################
            $countQuery = DB::table('orders')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select('orders.id')
            ->groupBy('orders.id');
            

            $countQueryWithFilter =  $this->filterOrdersWithItemCount($params,$countQuery);
            $totalRecords = $countQueryWithFilter->get()->count();
          
            
            return [
            'totalRecords' => $totalRecords,
            'orders' => $orders
            ];
                        
    
        }
        
        return Null;
    }
}
