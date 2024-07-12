<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NationalAssemblyAddRequest;
use App\Http\Requests\Backend\NationalAssemblyUpdateRequest;
use App\Models\NationalAssembly;
use Illuminate\Http\Request;

class NationalAssemblyController extends Controller
{
    public function index(Request $request)
    {
        $query =  NationalAssembly::select('*')->orderBy('id', 'ASC');
        $name  = $request->input('name');
        if($name) {
            $query->where('national_assemblies.name', 'LIKE', '%' . $name . '%');
        }
        $nAs = $query->paginate(config('constants.per_page'));
        
        $data = [
            'nAs'  => $nAs,
            'name' => $name,
        ]; 
        return view('backend.new_area_grid.na.index')->with($data);
    }

    public function addNationalAssemblyForm()
    {
        return view('backend.new_area_grid.na.create');
    }

    public function create(NationalAssemblyAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = NationalAssembly::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('na.index')->with('success', 'National Assembly has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit($id)
    {
        $nA = NationalAssembly::find($id);
        $data = [
            'nA' => $nA,
        ];
        return view('backend.new_area_grid.na.edit')->with($data);
    }

    public function update(NationalAssemblyUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $nAId = $request->input('na_id');
        $updated = NationalAssembly::where(['id'=>$nAId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('na.index')->with('success', 'National Assembly has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($nAId)
    {
        $nA    = new NationalAssembly(); 
        $deleted    = $nA->deleteNationalAssembly($nAId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'National Assembly has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
