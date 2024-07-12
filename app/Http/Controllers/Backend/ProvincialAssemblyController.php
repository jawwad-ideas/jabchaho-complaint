<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProvincialAssemblyAddRequest;
use App\Http\Requests\Backend\ProvincialAssemblyUpdateRequest;
use App\Models\ProvincialAssembly;
use Illuminate\Http\Request;

class ProvincialAssemblyController extends Controller
{
    public function index(Request $request)
    {
        $query =  ProvincialAssembly::select('*')->orderBy('id', 'ASC');
        $name  = $request->input('name');
        if($name) {
            $query->where('provincial_assemblies.name', 'LIKE', '%' . $name . '%');
        }
        $pAs = $query->paginate(config('constants.per_page'));
        
        $data = [
            'pAs' => $pAs,
            'name'      => $name,
        ];
        return view('backend.new_area_grid.pa.index')->with($data);
    }

    public function addProvincialAssemblyForm()
    {
        return view('backend.new_area_grid.pa.create');
    }

    public function create(ProvincialAssemblyAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = ProvincialAssembly::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('pa.index')->with('success', 'Provincial Assembly has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit($id)
    {
        $pA = ProvincialAssembly::find($id);
        $data = [
            'pA' => $pA,
        ];
        return view('backend.new_area_grid.pa.edit')->with($data);
    }

    public function update(ProvincialAssemblyUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $pAId = $request->input('pa_id');
        $updated = ProvincialAssembly::where(['id'=>$pAId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('pa.index')->with('success', 'Provincial Assembly has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($pAId)
    {
        $pA    = new ProvincialAssembly(); 
        $deleted = $pA->deleteProvincialAssembly($pAId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'Provincial Assembly has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
