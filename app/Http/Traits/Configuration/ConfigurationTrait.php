<?php
namespace App\Http\Traits\Configuration;

use App\Models\Configuration; 
use Illuminate\Support\Arr;

trait ConfigurationTrait {
    
    public function getConfigurations($filters=array()) 
    {   
        $configurations =array();
        
        if(!empty($filters))
        {
            $configurationsResutSet = Configuration::whereIn('name', $filters)->get()->toArray();
        }else
        {
            $configurationsResutSet = Configuration::get()->toArray();
        }

        
        if(!empty($configurationsResutSet))
        {
            foreach($configurationsResutSet as $row)
            {
                $configurations[Arr::get($row, 'name')]          = Arr::get($row, 'value');
            }

        }

        return $configurations;
    }
}