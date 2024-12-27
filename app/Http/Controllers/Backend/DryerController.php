<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use App\Models\Dryer;
use App\Http\Requests\Backend\StoreDryerDetailRequest;

use Illuminate\Http\Request;

class DryerController extends Controller
{
    public function index(Request $request)
    {
        $data =  $filterData = array();

        $query              = Dryer::orderBy('id', 'desc');
        $dryerlots          = $query->latest()->paginate(config('constants.per_page'));

        $data['dryerlots']      = $dryerlots;

        return view('backend.dryer.index')->with($data)->with($filterData);
    }
    
    
    public function create()
    {
        $data           = array();
        $filterData     = array();
        
        return view('backend.dryer.create')->with($data);
    }

    public function save( StoreDryerDetailRequest $request )
    {
       
        $postData = $request->validated();

         // Clean up the string and convert to an array
        $beforeBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'before_barcodes')))
        );

        $beforeBarcodeArray          = array_unique($beforeBarcodeArray);
        $beforeBarcodeCommaSeparated = implode(',', $beforeBarcodeArray);

        $afterBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'after_barcodes')))
        );

        $afterBarcodeArray          = array_unique($afterBarcodeArray);
        $afterBarcodeCommaSeparated = implode(',', $afterBarcodeArray);

        $dryer                       = array();
        $dryer['status']             = config('constants.dryer_statues_id.pending');
        $dryer['before_barcodes']    = $beforeBarcodeCommaSeparated;
        $dryer['after_barcodes']     = $afterBarcodeCommaSeparated;

        $isInserted = Dryer::insert($dryer);

        return redirect()->route('sunny.dryer')
                ->with('success', 'Sunny Dry Detail Saved Successfully.');

    }


     /**
     * Edit dryer data
     * 
     * @param Dryer $dryer
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Dryer $dryer) 
    {
        $afterBarcodesNewLineSeparated = $beforeBarcodesNewLineSeparated = '';
        $beforeBarcodes = Arr::get($dryer,'before_barcodes');
        
        if(!empty($beforeBarcodes))
        {
            $beforeBarcodesNewLineSeparated = str_replace(',', "\n", $beforeBarcodes);
        }
                
        $afterBarcodes = Arr::get($dryer,'after_barcodes');
        if(!empty($afterBarcodes))
        {
            $afterBarcodesNewLineSeparated = str_replace(',', "\n", $afterBarcodes);
        }
        
        
        return view('backend.dryer.edit', [
            'dryer'                          => $dryer,
            'beforeBarcodesNewLineSeparated' => $beforeBarcodesNewLineSeparated,
            'afterBarcodesNewLineSeparated'  => $afterBarcodesNewLineSeparated
        ]);
    }

     /**
     * Update dryer data
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Dryer $dryer, StoreDryerDetailRequest $request) 
    {
        $postData = $request->validated();

        $afterBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'after_barcodes')))
        );

        $afterBarcodeArray          = array_unique($afterBarcodeArray);
        $afterBarcodeCommaSeparated = implode(',', $afterBarcodeArray);

        $dryerData                       = array();
        $dryerData['status']             = config('constants.dryer_statues_id.approved');
        $dryerData['after_barcodes']     = $afterBarcodeCommaSeparated;

        $dryer->update($dryerData);

        return redirect()->route('sunny.dryer')
        ->with('success', 'Sunny Dry Detail Saved Successfully.');

    }
}
