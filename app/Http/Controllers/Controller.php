<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\ComplaintDocument;
use App\Traits\QueueWorker\QueueWorkerTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,QueueWorkerTrait;

    #excetion response
    public function getCustomExceptionMessage($exception='')
    {
        
        \Log::info($exception);
        //return '******Whoops, looks like something went wrong*****';
        return view('errors.500');
    }


    #Remove image
    public function removeFile($fileNameWithPath='')
    {
        try{

            if(\File::exists($fileNameWithPath)){
                \File::delete($fileNameWithPath);
            }

            return true;

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }	
    }

    #Remove image
    public function removeTempFile($fileNameWithPath='')
    {
        try{

			if (Storage::disk('local')->exists($fileNameWithPath)) 
            {
                $deleted = Storage::disk('local')->delete($fileNameWithPath);
            }

            return true;

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }	
    }

    public function uploadImages($request=null,$complaintId=0)
    {
        $files = $request->file('attachments');
  
        if(!empty($files))
        {
            $counter = 1;
            $complaintDocumnets = array(); 
            foreach($files as $fieldName =>$file)
            {
                if(!empty($file))
                {
                    
                    $uploadFolderPath = config('constants.files.complaint_documents');
                    $filePath = public_path($uploadFolderPath);
                    $filename = $file->getClientOriginalName(); // Get original filename
                    $fileExtension = strtolower($file->guessExtension()?$file->guessExtension():$file->getClientOriginalExtension());
                    $uniqueName = time().'-'.uniqid().'-'.$complaintId.'-'.$counter;
                    $newName = $uniqueName. '.' . $fileExtension; // Generate unique name
                    $file->move($filePath, $newName);

                    $complaintDocumnet                      = array();
                    $complaintDocumnet['complaint_id']      = $complaintId;
                    $complaintDocumnet['document_name']     = config('constants.document_name.complaint');
                    $complaintDocumnet['file']              = $newName;
                    $complaintDocumnet['original_file']     = $filename;
                    
                    $complaintDocumnets[$counter] = $complaintDocumnet;           
                }

                $counter++;
            }

            ComplaintDocument::insert($complaintDocumnets);
        }        
    }
}
