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
        $barcode               = $request->input('barcode');
        $from                  = $request->input('from');
        $to                    = $request->input('to');

        $query              = Dryer::orderBy('id', 'desc');
        if (!empty($status)) 
        {
            $query->where('status', '=',  $status);
        }

        if (!empty($barcode)) {
            $barcodes = explode(',', $barcode);
        
            $query->where(function($q) use ($barcodes) {
                foreach ($barcodes as $singleBarcode) {
                    $q->orWhere('barcode', 'like', '%' . trim($singleBarcode) . '%');
                }
            });
        }

        
        if (!empty($from) && !empty($to)) 
        {
            $strat   = $from." 00:00:00";
            $end     = $to." 23:59:59";

            if( $status == config('constants.dryer_statues_id.completed') ){
                $query->whereBetween('updated_at', [$strat,$end]);
            }
            else{
                $query->whereBetween('created_at', [$strat,$end]);
            }
            
        } 


        $dryerlots          = $query->latest()->paginate(config('constants.per_page'));

        $data['dryerlots']                  = $dryerlots;
        $filterData['status']               = $status;
        $filterData['barcode']              = $barcode;
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
        $records  = [];
        $duplicateBarcodes = [];
        $postData = $request->validated();

         // Clean up the string and convert to an array
        $barcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'barcode')))
        );

        if (!empty($barcodeArray)) 
        {
            // Remove duplicate barcode values from the input array
            $barcodeArray = array_unique($barcodeArray);
        
            // Fetch existing barcodes from the database
            $existingBarcodes = Dryer::whereIn('barcode', $barcodeArray)
                ->pluck('barcode')
                ->toArray();
        
            // Build the records array only for barcodes that do not already exist
            foreach ($barcodeArray as $barcode) {
                if (in_array($barcode, $existingBarcodes)) {
                    $duplicateBarcodes[] = $barcode;
                } else {
                    $records[] = [
                        'status'     => config('constants.dryer_statues_id.pending'),
                        'barcode'    => $barcode,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert new records if any
        if (!empty($records)) 
        {
            Dryer::insert($records);
        }
    
        // Optionally, flash a message for duplicates
        $successMessage = '';
        $message ='';
        if (!empty($duplicateBarcodes)) 
        {
            $message = "The following barcodes already exist: " . implode(', ', $duplicateBarcodes).".";;

            if(count($duplicateBarcodes) != count($barcodeArray) )
            {
                $successMessage= "The remaining barcodes added successfully.";
            }
            return redirect()->route('sunny.dryer',config('constants.dryer_statues_id.pending'))->withErrors($message)->with('success', $successMessage);;
        } 
        else 
        {
            $message = "Sunny Dry Detail Saved Successfully.";
            return redirect()->route('sunny.dryer',config('constants.dryer_statues_id.pending'))
            ->with('success', $message);
        }

        


       

    }

    public function markedCompleteForm()
    {
        $data           = array();
        $filterData     = array();
        
        return view('backend.dryer.markedComplete')->with($data);
    }


    public function markedComplete(StoreDryerDetailRequest $request)
    {
        $notFoundBarcodes = [];
        $postData = $request->validated();

         // Clean up the string and convert to an array
        $barcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'barcode')))
        );

        if (!empty($barcodeArray)) 
        {
            // Remove duplicate barcode values
            $barcodeArray = array_unique($barcodeArray);
            
            foreach ($barcodeArray as $barcode) {
                // Attempt to update the record with the given barcode
                $updated = Dryer::where(['barcode' => $barcode, 'status' => config('constants.dryer_statues_id.pending')])
                    ->update(['status' => config('constants.dryer_statues_id.completed')]);
        
                // If no records were updated, the barcode wasn't found
                if ($updated == 0) {
                    $notFoundBarcodes[] = $barcode;
                }
            }
        }


        $successMessage = '';
        $message ='';
        if (!empty($notFoundBarcodes)) 
        {
            $message = "The following barcodes were not found:".implode(', ', $notFoundBarcodes).".";

            if(count($notFoundBarcodes) != count($barcodeArray) )
            {
                $successMessage= "The remaining barcodes have been marked as completed.";
            }

            return redirect()->route('sunny.dryer',config('constants.dryer_statues_id.pending'))->withErrors($message)->with('success', $successMessage);
        } 
        else 
        {
            $message = "All barcodes marked as complete successfully.";
            return redirect()->route('sunny.dryer',config('constants.dryer_statues_id.pending'))->with('success', $message);
        }

        
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
