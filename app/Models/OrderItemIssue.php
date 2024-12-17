<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItemIssue extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_item_issues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'issue'
    ];


    // Define the inverse of the relationship
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'id');
    }

    public function getItemissuesCount($params=array())
    {
        $query = OrderItemIssue::select('issue', DB::raw('COUNT(*) as count')) // Using the Eloquent model for the `order_item_issues` table.
                ->when(!empty($params['startDate']) && !empty($params['endDate']), function ($query) use ($params) {
                    $query->whereBetween('created_at', [$params['startDate'], $params['endDate']]); // Apply date filter if present
                })
                ->groupBy('issue'); // Group by 'issue' after the condition check

            $issueCounts = $query->get(); // Execute the query and fetch results.
            return $issueCounts;
    }
}
