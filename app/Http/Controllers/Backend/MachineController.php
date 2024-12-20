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
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class MachineController extends Controller
{

    public function machineAdd(){
        $filterData["statusOptions"] = [0 => "Inactive",1 => "Active"];
        return view('backend.machine.machineAdd')->with($filterData);
    }
    public function machineSave( Request $request ){
        $status            = $request->get('is_enabled');
        $name              = $request->get('name');

        $data = ["name" => $name , "is_enabled" => $status ];
        if ($request->has('machine_id')) {
            $machineId              = $request->get('machine_id');
            $machine = Machine::where(['id' => $machineId])->first();
            $data["updated_at"] = now();
            $machine->update(
                $data
            );
            $message = "Machine has been updated successfully";;
        }else{
            $machine = new Machine();
            $machineId = $machine->createMachine($data);
            $message = "Machine has been added successfully";
        }

        return redirect()->route('machine.list', ['machine_id' => $machineId])
            ->with('success', $message );

    }

    public function machineView( $machineId ){
        $machine = Machine::where(['id' => $machineId])->first();
        return view('backend.machine.machineEdit', [
            'machine' => $machine,
            'statusOptions' => [0 => "Inactive",1 => "Active"]
        ]);
    }



    public function machineIndex(Request $request)
    {
        $machinesQuery = Machine::select();

        // Apply filters if needed
        if ($request->has('name') && !empty($request->name)) {
            $machinesQuery->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('is_enabled')) {
            $machinesQuery->where('is_enabled', $request->is_enabled);
        }
        // Paginate the query and order by latest
        $machines = $machinesQuery->latest('id')->paginate(config('constants.per_page'));

        $filterData = [
            'name'          => $request->get('name', ''), // Default to empty
            'is_enabled'    => $request->get('is_enabled', null), // Default to null if not provided
            'statusOption'  => [0 => "Inactive", 1 => "Active"],
        ];

        return view('backend.machine.machineIndex', compact('machines'))->with($filterData);
    }

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

                    $uploadFolderPath         = config('constants.files.machines').$machineDetailId;
                    $thumbnailFolderPath      = $uploadFolderPath . '/thumbnail';
                    $filePath                 = public_path($uploadFolderPath);
                    $thumbnailPath            = public_path($thumbnailFolderPath);

                    if (!File::exists($filePath)) {
                        File::makeDirectory($filePath, 0777, true, true);
                    }

                    if (!File::exists($thumbnailPath)) {
                        File::makeDirectory($thumbnailPath, 0777, true, true);
                    }

                    $filename = $file->getClientOriginalName(); // Get original filename
                    $fileExtension = strtolower($file->guessExtension()?$file->guessExtension():$file->getClientOriginalExtension());
                    $uniqueName = time().'-'.uniqid().'-'.$machineDetailId.'-'.$counter;
                    $newName = $uniqueName. '.' . $fileExtension; // Generate unique name

                    $imageAttachmentItem = Image::make($file->getPathname());
                    // Compress the image quality (e.g., 60%)
                    $imageAttachmentItem->save($filePath . '/' . $newName, 60);


                    $thumbnail = Image::make($file->getRealPath())
                        ->resize(150, 150, function ($constraint) {
                            $constraint->aspectRatio(); // Maintain aspect ratio
                            $constraint->upsize();     // Prevent upsizing
                        });

                    $thumbnail->save($thumbnailPath . '/' . $newName, 60);



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
        
        $machineType        = $request->input('machine_type');
        $from               = $request->input('from');
        $to                 = $request->input('to');
        $barcode          = $request->input('barcode');
        
        $query              = MachineDetail::with('machine')->orderBy('id', 'desc');
        
        //Filters apply here
        if (!empty($machineType)) 
        {
            $query->whereHas('machine', function ($q) use ($machineType) {
                $q->where('name', 'like', '%' . $machineType . '%');
            });
        } 

        if (!empty($from) && !empty($to)) 
        {
            $query->whereBetween('created_at', [$from,$to]);
        } 


        if (!empty($barcode)) 
        {
            $query->whereHas('machineBarcodes', function ($q) use ($barcode) {
                $q->where('barcode', 'like', '%' . $barcode . '%');
            });
        } 

        $machineDetails                 = $query->latest()->paginate(config('constants.per_page'));
        $data['machineDetails']         = $machineDetails;


        $filterData = [
            'machineType'   => $machineType,
            'from'          => $from,
            'to'            => $to,
            'barcode'       => $barcode
        ];

        return view('backend.machine.index')->with($data)->with($filterData);
    }


     /**
     * Display machine detail
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $machineDetailObject = new MachineDetail;

        $machineDetailId        = $request->route('machineDetailId');
        $machineDetailData      = $machineDetailObject->getMachineDetailById($machineDetailId);

        if(!empty($machineDetailData))
        {
            $data                           = array();
            $data['machineDetailData']      = $machineDetailData;
            return view('backend.machine.show')->with($data);
        }
        else
        {
            return redirect()->route('machine.details')->withErrors(['error' => "Invalid Machine Details"]);
        }

    }

}
