<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NewAreaAddRequest;
use App\Models\NewArea;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\NewAreaUpdateRequest;
use Illuminate\Support\Arr;


class NewAreaController extends Controller
{
    public function index(Request $request)
    {
        $query = NewArea::select('*')->orderBy('id', 'ASC');
        
        $name = $request->input('name');
        $city = $request->input('city_id');
        if($name) {
            $query->where('new_areas.name', 'LIKE', '%' . $name . '%');
        }
        if($city)
        {
            $query->where('new_areas.city_id','=',$city);
        }
        
        $newAreas = $query->paginate(config('constants.per_page'));
        $cities = City::all()->whereNull('deleted_at');
        $data = [
            'newAreas' => $newAreas,
            'name'     => $name,
            'cities'   => $cities,
        ];
        
        return view('backend.new_area_grid.new_area.index')->with($data);
    }


    public function edit(NewArea $newArea)
    {
        $cities = City::all();
        return view('backend.new_area_grid.new_area.edit', [
            'newArea' => $newArea,
            'cities'  => $cities,
        ]);
    }

    public function update(NewAreaUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $newAreaId = $request->input('new_area_id');
        $updated = NewArea::where(['id'=>$newAreaId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('new.area.index')->with('success', 'New Area has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($newAreaId)
    {
        $newArea    = new NewArea; 
        $deleted    = $newArea->deleteNewArea($newAreaId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'New Area has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function addAreaForm()
    {
        $cities = City::all();
        return view('backend.new_area_grid.new_area.create', [
            'cities'  => $cities,
        ]);
    }

    public function create(NewAreaAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = NewArea::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('new.area.index')->with('success', 'New Area has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
