<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LaundryItemServices extends Model
{
    use HasFactory;

    //get pricing jabchaho
    public function getPricing()
    {
        $results = DB::connection('laundry_mysql')
                    ->table('laundry_item_services as lis')
                    ->join('services as s', 'lis.services_id', '=', 's.id')
                    ->join('laundry_items as li', 'lis.laundry_item_id', '=', 'li.id')
                    ->join('laundry_item_category as lic', 'li.laundry_item_category_id', '=', 'lic.id')
                    ->select(
                        'lic.name as category_name',
                        's.name as service',
                        'li.name as laundry_item_name',
                        'price',
                        'e_price',
                        's.icon as service_icon',
                        's.status as service_status',
                        'li.icon as laundry_item_icon',
                        'li.status as laundry_item_status'
                    )
                    ->where('s.status', 1)
                    ->where('li.status', 1)
                    ->where('lic.status', 1)
                ->get()->toArray();

        $resultsArray = json_decode(json_encode($results), true);

        return $resultsArray;                
    }
}
