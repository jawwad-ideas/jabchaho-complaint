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
        'location_type',
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
            SUM(CASE WHEN location_type IS NOT NULL THEN 1 ELSE 0 END) as store,
            SUM(CASE WHEN location_type IS NULL THEN 1 ELSE 0 END) as facility,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as process,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN before_email = 2 THEN 1 ELSE 0 END) as before_email,
            SUM(CASE WHEN final_email = 2 THEN 1 ELSE 0 END) as final_email
        ');

        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $result = $query->first();
        $data = [
            'total' => (int) $result->total,
            'store' => (int) $result->store,
            'facility' => (int) $result->facility,
            'process' => (int) $result->process,
            'completed' => (int) $result->completed,
            'before_email' => (int) $result->before_email,
            'after_email' => (int) $result->final_email,
        ];

        return $data;
    }

    function filterOrdersWithItemCount($params=array(),$query =null)
    {
        $customerName   = Arr::get($params, 'name');
        $telephone      = Arr::get($params, 'telephone');
        $orderNumber    = Arr::get($params, 'orderNumber');
        $locationType   = Arr::get($params, 'locationType');
        $issueType      = Arr::get($params, 'issueType');
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

        if (!empty($locationType)) {
            
            if($locationType == strtolower(config('constants.laundry_location_type.store')))
            {
                $query->whereNotNull('orders.location_type');
            } 
            else
            {
                $query->whereNull('orders.location_type');
            }
        }

        if (!empty($issueType)) {
            $query->where('order_item_issues.issue', '=', $issueType); // Filter by issue type
        }

        return $query;
    }



    function getOrdersWithItemCount($params=array())
    {
        $customerName   = Arr::get($params, 'name');
        $telephone      = Arr::get($params, 'telephone');
        $orderNumber    = Arr::get($params, 'orderNumber');
        $locationType   = Arr::get($params, 'locationType');
        $issueType      = Arr::get($params, 'issueType');
        $startDate      = Arr::get($params, 'startDate');
        $endDate        = Arr::get($params, 'endDate');
        $page           = Arr::get($params, 'page');
        $limit          = Arr::get($params, 'limit');
        
        $orders = Null;
        if($customerName || $telephone || $orderNumber ||$locationType ||$issueType)
        {
            
            $query = Order::select(
                'orders.id',
                'orders.customer_name',
                'orders.telephone',
                'orders.location_type',
                'orders.order_id as order_id',
                DB::raw('COUNT(order_items.id) as item_count')
            )
            ->withCount([
                'before', // Count with image_type = 1
                'after', // Count with image_type = 2
            ])
            ->leftjoin('order_items', 'orders.id', '=', 'order_items.order_id') // Join orders with order_items
            ->leftjoin('order_item_issues', 'order_items.id', '=', 'order_item_issues.item_id')
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
            ->leftjoin('order_item_issues', 'order_items.id', '=', 'order_item_issues.item_id')
            ->select('orders.id')
            ->groupBy('orders.id');
            

            $countQueryWithFilter =  $this->filterOrdersWithItemCount($params,$countQuery);
            $totalRecords = $countQueryWithFilter->get()->count();
          
            
            return [
            'totalRecords' => $totalRecords,
            'orders' => $orders
            ];
                        
    
        }

        return [
            'totalRecords' => 0,
            'orders' => []
            ];
    
    }
}
