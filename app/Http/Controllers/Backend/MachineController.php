<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineDetail;
use App\Models\MachineImage;
use App\Models\MachineBarcode;
use App\Http\Requests\Backend\StoreMachineDetailRequest;
use Illuminate\Support\Arr;

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



    public function store(StoreMachineDetailRequest $request)
    {
        $postData = $request->validated();

        $machineDetail = array('machine_id' => Arr::get($postData,'machine_id'));
        $machineDetailId = MachineDetail::insertGetId($machineDetail);

        if(!empty(Arr::get($postData,'barcode')))
        {
            // Clean up the string and convert to an array
            $barcodeArray = array_filter(
                array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'barcode')))
            );
        }
    }
}
