<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Http\Requests\Backend\StoreMachineDetailRequest;

use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function detailForm()
    {
        $data           = array();
        $filterData     = array();

        $machines       = Machine::where(['is_enabled' => 1])->get();

        $data['machines']   = $machines;
        return view('backend.machine.detailForm')->with($data)->with($filterData);
    }



    public function save(StoreMachineDetailRequest $request)
    {
        // Get the input value from the textarea
        $textareaValue = $request->input('remarks');

        // Split the lines into an array
        $lines = explode("\n", $textareaValue);

        // Remove extra whitespace or empty lines
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines);

        // Convert the array to a comma-separated string
        $commaSeparated = implode(',', $lines);

        // Use or return the result
        return response()->json(['commaSeparated' => $commaSeparated]);
    }
}
