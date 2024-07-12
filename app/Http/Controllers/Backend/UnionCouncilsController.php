<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UnionCouncilAddRequest;
use App\Http\Requests\Backend\UnionCouncilUpdateRequest;
use App\Models\UnionCouncil;
use Illuminate\Http\Request;

class UnionCouncilsController extends Controller
{
    public function index()
    {
        $unionCouncils =  UnionCouncil::select('*')->paginate(config('constants.per_page'));
        $data = [
            'unionCouncils' => $unionCouncils,
        ];
        return view('backend.new_area_grid.union_council.index')->with($data);
    }

    public function addUnionCouncilForm()
    {
        return view('backend.new_area_grid.union_council.create');
    }

    public function create(UnionCouncilAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = UnionCouncil::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('union.council.index')->with('success', 'Union Council has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit(UnionCouncil $unionCouncil)
    {
        return view('backend.new_area_grid.union_council.edit', [
            'unionCouncil' => $unionCouncil,
        ]);
    }

    public function update(UnionCouncilUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $unionCouncilId = $request->input('union_council_id');
        $updated = UnionCouncil::where(['id'=>$unionCouncilId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('union.council.index')->with('success', 'Union Council has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($unionCouncilId)
    {
        $unionCouncil    = new UnionCouncil; 
        $deleted    = $unionCouncil->deleteUnionCouncil($unionCouncilId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'Union Council has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
