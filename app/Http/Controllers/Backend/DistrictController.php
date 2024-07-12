<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DistrictAddRequest;
use App\Http\Requests\Backend\DistrictUpdateRequest;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $query =  District::select('*')->orderBy('id', 'ASC');
        $name  = $request->input('name');
        if($name) {
            $query->where('districts.name', 'LIKE', '%' . $name . '%');
        }
        $districts = $query->paginate(config('constants.per_page'));
        
        $data = [
            'districts' => $districts,
            'name'      => $name,
        ];

        return view('backend.new_area_grid.district.index')->with($data);
    }

    public function addDistrictForm()
    {
        return view('backend.new_area_grid.district.create');
    }

    public function create(DistrictAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = District::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('district.index')->with('success', 'District has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit(District $district)
    {
        return view('backend.new_area_grid.district.edit', [
            'district' => $district,
        ]);
    }

    public function update(DistrictUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $districtId = $request->input('district_id');
        $updated = District::where(['id'=>$districtId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('district.index')->with('success', 'District has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($districtId)
    {
        $district    = new District; 
        $deleted    = $district->deleteDistrict($districtId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'District has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

}
