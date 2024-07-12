<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WardAddRequest;
use App\Http\Requests\Backend\WardUpdateRequest;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards =  Ward::select('*')->paginate(config('constants.per_page'));
        $data = [
            'wards' => $wards,
        ];
        return view('backend.new_area_grid.ward.index')->with($data);
    }

    public function addWardForm()
    {
        return view('backend.new_area_grid.ward.create');
    }

    public function create(WardAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = Ward::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('ward.index')->with('success', 'Ward has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit(Ward $ward)
    {
        return view('backend.new_area_grid.ward.edit', [
            'ward' => $ward,
        ]);
    }

    public function update(WardUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $wardId = $request->input('ward_id');
        $updated = Ward::where(['id'=>$wardId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('ward.index')->with('success', 'Ward has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($wardId)
    {
        $ward    = new Ward; 
        $deleted    = $ward->deleteWard($wardId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'Ward has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
