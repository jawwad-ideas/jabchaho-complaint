<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\NewAreaGrid;
use Illuminate\Http\Request;

class NewAreaGridController extends Controller
{
    public function index()
    {
        $newAreaGrid = NewAreaGrid::select('*');
        $newAreaGridResults = $newAreaGrid->orderBy('id', 'DESC')->paginate(config('constants.per_page'));
        $data['newAreaGrid'] = $newAreaGridResults;
        return view('backend.new_area_grid.index')->with($data);
    }

    public function destroy($newAreaGridId)
    {
        $newAreaGridObject = new NewAreaGrid;
        $deleted = $newAreaGridObject->deleteNewAreaGrid($newAreaGridId);
        if($deleted)
        {
            return redirect()->back()->with('success', 'New Area Grid has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }
}
