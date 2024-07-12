<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Models\NewAreaGrid;
use App\Models\UserWiseAreaMapping;
use Illuminate\Validation\ValidationException;

class AjaxController extends Controller
{
    public function getData(Request $request)
    {
        $result = array();
        $response = "";

        $className      = $request->segment(3);
        $fieldName      = $request->segment(4);
        $fieldId        = $request->segment(5);
        $fieldName2      = $request->segment(6);
        $fieldId2        = $request->segment(7);
        $fieldName3      = $request->segment(6);
        $fieldId3        = $request->segment(7);

        if(!empty($className) && !empty($fieldName) && !empty($fieldId))
        {
            //$className = "App".DIRECTORY_SEPARATOR."Models".DIRECTORY_SEPARATOR.$className;
            $className = "App\\Models\\" . $className;
            $classObject = new $className();

            if($request->segment(3) == 'Ward')
            {
                $result = $classObject::where([$fieldName2 => $fieldId2,$fieldName => $fieldId])->get()->toArray();
            }
            else if($request->segment(3) == 'Complaint')
            {
                $result = $classObject::where([$fieldName => $fieldId,$fieldName2 => $fieldId2,$fieldName3 => $fieldId3])->get()->toArray();
            }
            else
            {
                $result = $classObject::where([$fieldName => $fieldId])->get()->toArray();
            }



            if(!empty($result))
            {
                foreach($result as $row)
                {
                    $id     = Arr::get($row,'id');
                    if($request->segment(3) == 'Complaint'){
                        $id = Arr::get($row,'title');
                        $name   = Arr::get($row,'title');
                    }else if($request->segment(3) == 'Complainant'){
                        $id     = Arr::get($row,'mobile_number');
                        $name   = Arr::get($row,'mobile_number');
                    }else{
                        $name   = Arr::get($row,'name');
                    }
                    
                    if(!empty($name))
                    {
                        $response.= "<option value='".$id."' data-custom-attr='".$name."'>".$name."</option>";
                    }
                }
            }
        }






        return $response;

    }

    // public function getReport(Request $request)
    // {
    //     try {

    //         //return response()->json(['status'=> true , 'response' => $request->all() , 'message' => 'success']);
    //         $staringDate = $request->input('start_date');
    //         $endDate = $request->input('end_date');
    //         if($staringDate && $endDate){
    //             $this->validate($request, [
    //                 'start_date' => 'required|date',
    //                 'end_date' => 'required|date|after:start_date', // Validate after startDate
    //             ]);
    //         }
    //         $result = array();
    //         $response = "";
    //         $className = "Complaint";
    //         $fieldName = 'created_at';

    //         if(!empty($className))
    //         {
    //             //return response()->json(['status' => false,'message' => 'No Record Founddasdsa']);
    //             //$className = "App".DIRECTORY_SEPARATOR."Models".DIRECTORY_SEPARATOR.$className;
    //             $className = "App\\Models\\" . $className;
    //             $classObject = new $className();
    //             $result = $classObject::query()->select('complaints.*', 'cities.name as city_name','districts.name as district_name', 'sub_divisions.name as sub_divisions_name',
    //             'charges.name as charges_name','union_councils.name as union_councils_name','wards.name as wards_name','national_assemblies.name as national_assemblies_name',
    //             'provincial_assemblies.name as provincial_assemblies_name','new_areas.name as new_areas_name','complaints.description as description','complaints.title as title')
    //             ->join('cities', 'complaints.city_id', '=', 'cities.id')
    //             ->join('districts', 'complaints.district_id', '=', 'districts.id')
    //             ->join('sub_divisions', 'complaints.sub_division_id', '=', 'sub_divisions.id')
    //             ->join('charges', 'complaints.charge_id', '=', 'charges.id')
    //             ->join('union_councils', 'complaints.union_council_id', '=', 'union_councils.id')
    //             ->join('wards', 'complaints.ward_id', '=', 'wards.id')
    //             ->join('national_assemblies', 'complaints.national_assembly_id', '=', 'national_assemblies.id')
    //             ->join('provincial_assemblies', 'complaints.provincial_assembly_id', '=', 'provincial_assemblies.id')
    //             ->join('new_areas', 'complaints.new_area_id', '=', 'new_areas.id')
    //             ->join('complainants', 'complaints.complainant_id', '=', 'complainants.id');
                

    //             //conditions depends on their selection
    //             if ($request->has('start_date')) {
    //                 $result = $result->where('complaints.created_at', '>=', '2024-05-01');
    //             }

    //             if ($request->input('end_date')) {
    //                 $result = $result->where('complaints.created_at', '<=', $endDate);
    //             }

    //             if ($request->input('cnic')) {
    //                 $cnic = $request->input('cnic');
    //                 $result = $result->where('complainants.cnic', '=', $cnic);
    //             }

    //             if ($request->input('mobile_number')) {
    //                 $mobile_number = $request->input('mobile_number');
    //                 $result = $result->where('complainants.mobile_number', '=', $mobile_number);
    //             }

    //             if ($request->input('level_one')) {
    //                 $level_one = $request->input('level_one');
    //                 $result = $result->where('complaints.level_one', '=', $level_one);
    //             }

    //             if ($request->input('level_two')) {
    //                 $level_two = $request->input('level_two');
    //                 $result = $result->where('complaints.level_two', '=', $level_two);
    //             }

    //             if ($request->input('level_three')) {
    //                 $level_three = $request->input('level_three');
    //                 $result = $result->where('complaints.level_three', '=', $level_three);
    //             }


    //             if ($request->input('title')) {
    //                 $title = $request->input('title');
    //                 $result = $result->where('complaints.title', '=', $title);
    //             }


    //             if ($request->input('city_id')) {
    //                 $city_id = $request->input('city_id');
    //                 $result = $result->where('complaints.city_id', '=', $city_id);
    //             }


    //             if ($request->input('district_id')) {
    //                 $district_id = $request->input('district_id');
    //                 $result = $result->where('complaints.district_id', '=', $district_id);
    //             }

    //             if ($request->input('sub_division_id')) {
    //                 $sub_division_id = $request->input('sub_division_id');
    //                 $result = $result->where('complaints.sub_division_id', '=', $sub_division_id);
    //             }

    //             if ($request->input('union_council_id')) {
    //                 $union_council_id = $request->input('union_council_id');
    //                 $result = $result->where('complaints.union_council_id', '=', $union_council_id);
    //             }

    //             if ($request->input('charge_id')) {
    //                 $charge_id = $request->input('charge_id');
    //                 $result = $result->where('complaints.charge_id', '=', $charge_id);
    //             }

    //             if ($request->input('ward_id')) {
    //                 $ward_id = $request->input('ward_id');
    //                 $result = $result->where('complaints.ward_id', '=', $ward_id);
    //             }

    //             if ($request->input('national_assembly_id')) {
    //                 $national_assembly_id = $request->input('national_assembly_id');
    //                 $result = $result->where('complaints.national_assembly_id', '=', $national_assembly_id);
    //             }

    //             if ($request->input('provincial_assembly_id')) {
    //                 $provincial_assembly_id = $request->input('provincial_assembly_id');
    //                 $result = $result->where('complaints.provincial_assembly_id', '=', $provincial_assembly_id);
    //             }

    //             if ($request->input('new_area_id')) {
    //                 $new_area_id = $request->input('new_area_id');
    //                 $result = $result->where('complaints.new_area_id', '=', $new_area_id);
    //             }
    //             $result = $result->get();

    //             //return response()->json(['status' => false,'message' => $result->toRawSql()]);

    //             if(!empty($result))
    //             {
                    
    //                 foreach($result as $row)
    //                 {
    //                     $response .= '<tr>';
    //                     $title     = Arr::get($row,'title');
    //                     $description     = Arr::get($row,'description');
    //                     $city_name     = Arr::get($row,'city_name');
    //                     $district_name     = Arr::get($row,'district_name');
    //                     $sub_divisions_name     = Arr::get($row,'sub_divisions_name');
    //                     $charges_name     = Arr::get($row,'charges_name');
    //                     $union_councils_name     = Arr::get($row,'union_councils_name');
    //                     $wards_name     = Arr::get($row,'wards_name');
    //                     $national_assemblies_name     = Arr::get($row,'national_assemblies_name');
    //                     $provincial_assemblies_name     = Arr::get($row,'provincial_assemblies_name');
    //                     $new_areas_name     = Arr::get($row,'new_areas_name');
                        

    //                     $response.= "<td>".$title."</td>";
    //                     $response.= "<td>".$description."</td>";
    //                     $response.= "<td>".$city_name."</td>";
    //                     $response.= "<td>".$district_name."</td>";
    //                     $response.= "<td>".$sub_divisions_name."</td>";
    //                     $response.= "<td>".$charges_name."</td>";
    //                     $response.= "<td>".$wards_name."</td>";
    //                     $response.= "<td>".$national_assemblies_name."</td>";
    //                     $response.= "<td>".$provincial_assemblies_name."</td>";
    //                     $response.= "<td>".$new_areas_name."</td>";
    //                     $response.= "<td>".$union_councils_name."</td>";
    //                     $response .= '</tr>';
    //                 }
                    
    //             }else{
    //                 return response()->json(['status' => false,'message' => 'No Record Found']);
    //             }
    //         }

    //         return response()->json(['status'=> true , 'response' => $response , 'message' => 'success']);
    //     }catch(\Exception $e){
    //         // Handle validation errors
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ]); // Unprocessable Entity HTTP status code
    //     }
    // }

    public function getNewAreaGridData(Request $request)
    {
        $responseArray  = array();
    
        $newAreaId     = $request->segment(3);
        $resultSets = UserWiseAreaMapping::with(['newArea','district','nationalAssembly','provincialAssembly'])->where(['new_area_id' => $newAreaId])->get()->toArray();
       
        
        if(!empty($resultSets))
        {
            foreach($resultSets as $resultSet)
            {
                $responseArray['new_area'][Arr::get($resultSet['new_area'],'id')]                       = array('id'=>Arr::get($resultSet['new_area'],'id'), 'name'=>Arr::get($resultSet['new_area'],'name')); 
                //$responseArray['charge'][Arr::get($resultSet['charge'],'id')]                           = array('id'=>Arr::get($resultSet['charge'],'id'), 'name'=>Arr::get($resultSet['charge'],'name')); 
                $responseArray['district'][Arr::get($resultSet['district'],'id')]                       = array('id'=>Arr::get($resultSet['district'],'id'), 'name'=>Arr::get($resultSet['district'],'name'));  
                //$responseArray['sub_division'][Arr::get($resultSet['sub_division'],'id')]               = array('id'=>Arr::get($resultSet['sub_division'],'id'), 'name'=>Arr::get($resultSet['sub_division'],'name'));  
                //$responseArray['union_council'][Arr::get($resultSet['union_council'],'id')]             = array('id'=>Arr::get($resultSet['union_council'],'id'), 'name'=>Arr::get($resultSet['union_council'],'name')); 
                //$responseArray['ward'][Arr::get($resultSet['ward'],'id')]                               = array('id'=>Arr::get($resultSet['ward'],'id'), 'name'=>Arr::get($resultSet['ward'],'name')); 
                $responseArray['national_assembly'][Arr::get($resultSet['national_assembly'],'id')]     = array('id'=>Arr::get($resultSet['national_assembly'],'id'), 'name'=>Arr::get($resultSet['national_assembly'],'name')); 
                $responseArray['provincial_assembly'][Arr::get($resultSet['provincial_assembly'],'id')] = array('id'=>Arr::get($resultSet['provincial_assembly'],'id'), 'name'=>Arr::get($resultSet['provincial_assembly'],'name'));  
            }
            
        }

        return response()->json($responseArray);

    }
}

