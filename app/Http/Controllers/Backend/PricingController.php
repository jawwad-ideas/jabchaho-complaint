<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaundryItemServices;
use App\Helpers\Helper;
use Illuminate\Support\Arr;

class PricingController extends Controller
{
    public function index()
    {
        try
        {
            $laundryItemServicesObject = new LaundryItemServices;

            $pricingData = $laundryItemServicesObject->getPricing();

            $data['regular'] = $this->getPricingDetail($pricingData,config('constants.jabchaho_service.regular'));
            $data['express'] = $this->getPricingDetail($pricingData,config('constants.jabchaho_service.express'));

            return view('backend.pricing.index')->with($data);
        }
        catch(\Exception $e) 
        {
            \Log::error("PricingController->index->" . $e->getMessage());
            return false;
        }
    }


    public function getPricingDetail($pricingData=array(),$type = '')
    {
        $json = array();
        if(!empty($pricingData))
        {
            
            $price = 'price';
            if($type == config('constants.jabchaho_service.express'))
            {
                $price = 'e_price';
            }
            
            // The collection contains items
            foreach ($pricingData as $result)
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
        }

        return response()->json($json);
    }
}
