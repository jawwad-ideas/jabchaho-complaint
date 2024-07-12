<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SubDivisionAddRequest;
use App\Http\Requests\Backend\SubDivisionUpdateRequest;
use App\Models\SubDivision;
use Illuminate\Http\Request;

class SubDivisionController extends Controller
{
    public function index()
    {
        $subDivisions =  SubDivision::select('*')->paginate(config('constants.per_page'));
        $data = [
            'subDivisions' => $subDivisions,
        ];
        return view('backend.new_area_grid.sub_division.index')->with($data);
    }

    public function addSubDivisionForm()
    {
        return view('backend.new_area_grid.sub_division.create');
    }

    public function create(SubDivisionAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = SubDivision::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('sub.division.index')->with('success', 'Sub Division has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit(SubDivision $subDivision)
    {
        return view('backend.new_area_grid.sub_division.edit', [
            'subDivision' => $subDivision,
        ]);
    }

    public function update(SubDivisionUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $subDivisionId = $request->input('sub_division_id');
        $updated = SubDivision::where(['id'=>$subDivisionId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('sub.division.index')->with('success', 'Sub Division has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($subDivisionId)
    {
        $subDivision    = new SubDivision; 
        $deleted    = $subDivision->deleteSubDivision($subDivisionId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'Sub Division has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
