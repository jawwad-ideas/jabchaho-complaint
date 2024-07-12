<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ChargeAddRequest;
use App\Http\Requests\Backend\ChargeUpdateRequest;
use App\Models\Charge;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function index()
    {
        $charges =  Charge::select('*')->paginate(config('constants.per_page'));
        $data = [
            'charges' => $charges,
        ];
        return view('backend.new_area_grid.charge.index')->with($data);
    }

    public function addChargeForm()
    {
        return view('backend.new_area_grid.charge.create');
    }

    public function create(ChargeAddRequest $request)
    {
        $validateValues = $request->validated();
        $created = Charge::insertGetId($validateValues);
        if($created)
        {
            return redirect()->route('charge.index')->with('success', 'Charge has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }

    public function edit(Charge $charge)
    {
        return view('backend.new_area_grid.charge.edit', [
            'charge' => $charge,
        ]);
    }

    public function update(ChargeUpdateRequest $request)
    {
        $validateValues = $request->validated();
        $chargeId = $request->input('charge_id');
        $updated = Charge::where(['id'=>$chargeId])->update($validateValues);

        if($updated)
        {
            return redirect()->route('charge.index')->with('success', 'Charge has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }

    public function destroy($chargeId)
    {
        $charge    = new Charge; 
        $deleted    = $charge->deleteCharge($chargeId);
        
        if($deleted)
        {
            return redirect()->back()->with('success', 'Charge has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }
    }
}
