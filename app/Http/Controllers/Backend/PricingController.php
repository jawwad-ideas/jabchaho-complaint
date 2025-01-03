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
            $data = array();

            $data['regular'] = $this->getDataFromFile(config('constants.jabchaho_service.regular'));
            $data['express'] =$this->getDataFromFile(config('constants.jabchaho_service.express'));

            return view('backend.pricing.index')->with($data);
        }
        catch(\Exception $e) 
        {
            \Log::error("PricingController->index->" . $e->getMessage());
            return false;
        }
    }


    public function getDataFromFile($fileName = '')
    {
        $filePath = public_path(config('constants.files.pricing')).'/'.$fileName.'.json'; // Full path to the file

        if (file_exists($filePath)) 
        {
            $content = file_get_contents($filePath);
            // Decode the JSON content to use as an array or object
            $jsonData = json_decode($content, true); // Set to false if you want an object instead of an array
        
            // Do something with the $jsonData
            return response()->json($jsonData);
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


    public function syncPricing()
    {
        
        try
        {
            $laundryItemServicesObject = new LaundryItemServices;
            $pricingData = $laundryItemServicesObject->getPricing();

            $regular = $this->getPricingDetail($pricingData,config('constants.jabchaho_service.regular'));
            $express = $this->getPricingDetail($pricingData,config('constants.jabchaho_service.express'));
            
            $this->generateFile(config('constants.jabchaho_service.regular'),$regular);
            $this->generateFile(config('constants.jabchaho_service.express'),$express);

            return redirect()->route('pricing')
                ->with('success', 'Pricing Synced Successfully.');
        }
        catch(\Exception $e) 
        {
            \Log::error("PricingController->syncPricing->" . $e->getMessage());
            return false;
        }
    }

    public function generateFile($fileName='',$json=NULL)
    {
        if(!empty($json->getContent()))
        {
            $filePath = public_path(config('constants.files.pricing')).'/'.$fileName.'.json'; // Full path to the file
            if (!file_exists($filePath)) 
            {
                // Create the folder if it doesn't exist
                if (!file_exists(dirname($filePath))) 
                {
                    mkdir(dirname($filePath), 0755, true);
                }
            }
            
            // Create the file with initial content
            file_put_contents($filePath, $json->getContent());
            

        }
       
        return true;
    }
}
