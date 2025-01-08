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
        $status                = $request->segment(2);
        $lotNumber             = $request->input('lot_number');
        $beforeBarcodes        = $request->input('before_barcodes');
        $afterBarcodes         = $request->input('after_barcodes');
        $from                  = $request->input('from');
        $to                    = $request->input('to');

        $query              = Dryer::orderBy('id', 'desc');
        if (!empty($status)) 
        {
            $query->where('status', '=',  $status);
        }

        if (!empty($lotNumber)) 
        {
            $query->where('lot_number', '=',  $lotNumber);
        }

        if (!empty($beforeBarcodes)) 
        {
            $query->where('before_barcodes', 'like',  '%' . $beforeBarcodes . '%');
        }

        if (!empty($afterBarcodes)) 
        {
            $query->where('after_barcodes', 'like',  '%' . $afterBarcodes . '%');
        }

        if (!empty($from) && !empty($to)) 
        {
            $query->whereBetween('created_at', [$from,$to]);
        } 


        $dryerlots          = $query->latest()->paginate(config('constants.per_page'));

        $data['dryerlots']                  = $dryerlots;
        $filterData['status']               = $status;
        $filterData['lotNumber']            = $lotNumber;
        $filterData['beforeBarcodes']       = $beforeBarcodes;
        $filterData['afterBarcodes']        = $afterBarcodes;
        $filterData['from']                 = $from;
        $filterData['to']                   = $to;
        
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
       
        $beforeBarcodeCommaSeparated =$afterBarcodeCommaSeparated ='';
        $postData = $request->validated();

         // Clean up the string and convert to an array
        $beforeBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'before_barcodes')))
        );

        if(!empty($beforeBarcodeArray))
        {
            $beforeBarcodeArray          = array_unique($beforeBarcodeArray);
            $beforeBarcodeCommaSeparated = implode(',', $beforeBarcodeArray);
        }
       

        $afterBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'after_barcodes')))
        );

        
        if(!empty($afterBarcodeArray))
        {
            $afterBarcodeArray          = array_unique($afterBarcodeArray);
            $afterBarcodeCommaSeparated = implode(',', $afterBarcodeArray);
        }

        $dryer                       = array();
        $dryer['lot_number']         = Arr::get($postData,'lot_number');
        $dryer['status']             = config('constants.dryer_statues_id.pending');
        $dryer['before_barcodes']    = $beforeBarcodeCommaSeparated;
        $dryer['after_barcodes']     = $afterBarcodeCommaSeparated;

        $isInserted = Dryer::insert($dryer);

        return redirect()->route('sunny.dryer',config('constants.dryer_statues_id.pending'))
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
        $afterBarcodesNewLineSeparated = $beforeBarcodesNewLineSeparated = NULL;
        $beforeBarcodes = Arr::get($dryer,'before_barcodes');
        
        if(!empty($beforeBarcodes))
        {
            $beforeBarcodesNewLineSeparated = str_replace(',', "\n", $beforeBarcodes);
            $beforeBarcodesNewLineSeparated .= "\r\n";
        }
                
        $afterBarcodes = Arr::get($dryer,'after_barcodes');
        if(!empty($afterBarcodes))
        {
            $afterBarcodesNewLineSeparated = str_replace(',', "\n", $afterBarcodes);
            $afterBarcodesNewLineSeparated .= "\r\n";
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

        if(!empty($afterBarcodeArray))
        {
            $afterBarcodeArray          = array_unique($afterBarcodeArray);
            $afterBarcodeCommaSeparated = implode(',', $afterBarcodeArray);
        }

        $error = $this->matchBarcode($dryer,$postData);

        
        if(!empty($error))
        {
            $status = config('constants.dryer_statues_id.pending');
        }
        else
        {
            $status = config('constants.dryer_statues_id.completed');
        }
        
        $dryerData                       = array();
        $dryer['lot_number']             = Arr::get($postData,'lot_number');
        $dryerData['status']             = $status;
        $dryerData['after_barcodes']     = $afterBarcodeCommaSeparated;

        $dryer->update($dryerData);

        if($status == config('constants.dryer_statues_id.pending'))
        {
            return redirect()->route('sunny.dryer.edit',Arr::get($dryer,'id'))->withErrors($error);
        }
        else
        {
            return redirect()->route('sunny.dryer',$status)
             ->with('success', 'Sunny Dry Detail Saved Successfully.');
        }

    }


    public function matchBarcode($dryer=null,$postData= array())
    {
        $error = '';
        $beforeBarcodeArray = $afterBarcodeArray =array();
        // Fetch the `before_barcodes` from the database
        $beforeBarcode = Arr::get($dryer,'before_barcodes');//Dryer::where('id', $this->recordId)->value('before_barcodes');
        if(!empty($beforeBarcode))
        {
            $beforeBarcodeArray = array_map('trim', explode(',', $beforeBarcode));
        }

        $afterBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'after_barcodes')))
        );

        if(!empty($afterBarcodeArray))
        {
            $afterBarcodeArray          = array_unique($afterBarcodeArray);
        }

        // Check for elements in $beforeBarcodeArray that are not in $afterBarcodeArray
        $missingInAfter = array_diff($beforeBarcodeArray, $afterBarcodeArray);

        // Check for elements in $afterBarcodeArray that are not in $beforeBarcodeArray
        $extraInAfter = array_diff($afterBarcodeArray, $beforeBarcodeArray);

        if (!empty($missingInAfter))
        {
            $error.= '<b>Missing from before the dryer barcodes:</b> '.implode(', ',$missingInAfter)."\r\n";
        }

        if(!empty($extraInAfter))
        {
            $error.= '<b>Additional items from before the dryer barcodes:</b> '.implode(', ',$extraInAfter)."\r\n";
        }

        $error = nl2br(trim($error));

        return $error;
    }

    
}
