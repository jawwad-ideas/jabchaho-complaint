<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Illuminate\Support\Arr;

class PricingController extends Controller
{
    public function index($type='')
    {
        try
        {
            if (!empty($type) && in_array($type, config('constants.jabchaho_service')))
            {
                $price = 'price';
                if($type == config('constants.jabchaho_service.express'))
                {
                    $price = 'e_price';
                }

                $results = DB::connection('laundry_mysql')
                            ->table('laundry_item_services as lis')
                            ->join('services as s', 'lis.services_id', '=', 's.id')
                            ->join('laundry_items as li', 'lis.laundry_item_id', '=', 'li.id')
                            ->join('laundry_item_category as lic', 'li.laundry_item_category_id', '=', 'lic.id')
                            ->select(
                                'lic.name as category_name',
                                's.name as service',
                                'li.name as laundry_item_name',
                                $price,
                                's.icon as service_icon',
                                's.status as service_status',
                                'li.icon as laundry_item_icon',
                                'li.status as laundry_item_status'
                            )
                            ->where('s.status', 1)
                            ->where('li.status', 1)
                            ->where('lic.status', 1)
                            ->get()->toArray();
                
                $json = [];
                
                // Convert to an array explicitly
                $resultsArray = json_decode(json_encode($results), true);

                if (!empty($resultsArray)) 
                {
                    // The collection contains items
                    foreach ($resultsArray as $result)
                    { 
                        $category = Helper::transformString(Arr::get($result, 'category_name'));
                        $service  = Helper::transformString(Arr::get($result, 'service'));
                        
                        $item = 
                        [
                            "label" => Arr::get($result, 'laundry_item_name'), 
                            "price" => Arr::get($result, $price), 
                            "img" => Arr::get($result, 'laundry_item_icon'),
                        ];

                        // Build the JSON structure
                        if (!isset($json[$service])) {
                            $json[$service] = [];
                        }
                        if (!isset($json[$service][$category])) {
                            $json[$service][$category] = [];
                        }
                        $json[$service][$category][] = $item;
                    }

                } else 
                {
                    $json['message'] = 'No Record Found';
                }
            }
            else 
            {
                $json['message'] = 'InValid Type';
            }
            
            
        }
        catch(\Exception $e) 
        {
            $json['message'] = $e->getMessage();
        }

        return response()->json($json);

    }
}
