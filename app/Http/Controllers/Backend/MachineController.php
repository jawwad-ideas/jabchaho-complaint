<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineDetail;
use App\Models\MachineImage;
use App\Models\MachineBarcode;
use App\Http\Requests\Backend\StoreMachineDetailRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function create()
    {
        $data           = array();
        $filterData     = array();

        $machines       = Machine::where(['is_enabled' => 1])->get();

        $data['machines']   = $machines;
        return view('backend.machine.create')->with($data)->with($filterData);
    }



    public function save(StoreMachineDetailRequest $request)
    {
        $machineBarcodes = array();
        $postData = $request->validated();

        //insert in machine detail table
        $machineDetail = array('machine_id' => Arr::get($postData,'machine_id'));
        $machineDetailId = MachineDetail::insertGetId($machineDetail);

        //insert in machine barcode table
        if(!empty(Arr::get($postData,'barcode')))
        {
            // Clean up the string and convert to an array
            $barcodeArray = array_filter(
                array_map('trim', preg_split('/\r\n|\r|\n/', Arr::get($postData,'barcode')))
            );

           
            if(!empty($barcodeArray))
            {
                $barcodeArray = array_unique($barcodeArray);
                foreach($barcodeArray as $barcode)
                {
                    $machineBarcodes[] = array('machine_detail_id'=>$machineDetailId, 'barcode' =>$barcode);
                }
            }

        }

        $isInserted = MachineBarcode::insert($machineBarcodes);

        ////upload image insert in machine images table

        //Files upload code
        $this->uploadImages($request,$machineDetailId);

        return redirect()->route('machine.details')
                ->with('success', 'Machine detail saved successfully.');
    }


    public function uploadImages($request=null,$machineDetailId=0)
    {
        $files = $request->file('attachments');
  
        if(!empty($files))
        {
            $counter = 1;
            $machineImages = array(); 
            foreach($files as $fieldName =>$file)
            {
                if(!empty($file))
                {
                    
                    $uploadFolderPath = config('constants.files.machines').$machineDetailId;
                    $filePath = public_path($uploadFolderPath);
                    if (!File::exists($filePath)) {
                        File::makeDirectory($filePath, 0777, true, true);
                    }
                    $filename = $file->getClientOriginalName(); // Get original filename
                    $fileExtension = strtolower($file->guessExtension()?$file->guessExtension():$file->getClientOriginalExtension());
                    $uniqueName = time().'-'.uniqid().'-'.$machineDetailId.'-'.$counter;
                    $newName = $uniqueName. '.' . $fileExtension; // Generate unique name
                    $file->move($filePath, $newName);

                    $machineImage                           = array();
                    $machineImage['machine_detail_id']      = $machineDetailId;
                    $machineImage['file']                   = $newName;
                    
                    $machineImages[$counter] = $machineImage;           
                }

                $counter++;
            }

            MachineImage::insert($machineImages);
        }   
        
        return true;
    }

    /**
     * Display all machine detail
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $query                          = MachineDetail::with('machine')->orderBy('id', 'desc');
        $machineDetails                 = $query->latest()->paginate(config('constants.per_page'));
        $data['machineDetails']         = $machineDetails;

        return view('backend.machine.index')->with($data);
    }


    /**
     * Edit machine detail data
     * 
     * @param Review $machineDetail
     * 
     * @return \Illuminate\Http\Response
     */

     public function edit(MachineDetail $machineDetail)
     {
        dd($machineDetail);
        
        //  return view('backend.reviews.edit', [
        //      'review' => $review,
        //      'reviewStatuses' => config('constants.review_statues')
        //  ]);
     }


}
