<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Http\Requests\Api\CreateComplaintRequest;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ComplaintController extends Controller
{
   
    /**
     * create compalint.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(CreateComplaintRequest $request)
    {
        try
        {
            $responsearray                      = array();
            $responseStatus                     = false;
            $responseMessage                    = array();
            
            $validateValues                     = $request->validated();

            $insertData['query_type']           = Arr::get($validateValues, 'query_type');
            $insertData['complaint_type']       = Arr::get($validateValues, 'complaint_type');
            $insertData['inquiry_type']         = Arr::get($validateValues, 'inquiry_type');
            $insertData['order_id']             = Arr::get($validateValues, 'order_id');
            $insertData['name']                 = Arr::get($validateValues, 'name');
            $insertData['email']                = Arr::get($validateValues, 'email');
            $insertData['mobile_number']        = Arr::get($validateValues, 'mobile_number');
            $insertData['comments']             = Arr::get($validateValues, 'comments');
             
            
            $complaintData =array();
            $complaintData  = Complaint::create($insertData);
            $complaintId    = Arr::get($complaintData, 'id',0);

            $prefix =config('constants.complaint_number_starting_index'); //complaint_number_starting_index
            $complaintNumber = ($prefix + $complaintId);

            $complaintData->update(['complaint_number' => $complaintNumber]);

            //Files upload code
            $this->uploadImages($request,$complaintId);

            $this->sendEmailToComplainant($complaintId);

            if(!empty($complaintData))
            { 
                $responseStatus 	        = true;
                $responseMessage	        = 'Successful';
            }
            else
            {
                $responseMessage	        = array();
            }
            
            
            $responsearray['status'] 	        = $responseStatus;
            $responsearray['message'] 	        = $responseMessage;
        
            
            return response()->json($responsearray);
        }    
        catch(\Exception $e) 
        {
            \Log::error("api/ComplaintController -> create =>".$e->getMessage());
            return Helper::customErrorMessage();
        }
    }

    public function uploadImages($request=null,$complaintId=0)
    {
        $files = $request->allFiles();
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
                    $complaintDocumnet['document_name']     = $fieldName;
                    $complaintDocumnet['file']              = $newName;
                    $complaintDocumnet['original_file']     = $filename;
                    
                    $complaintDocumnets[$counter] = $complaintDocumnet;           
                }

                $counter++;
            }

            ComplaintDocument::insert($complaintDocumnets);
        }        
    }


    public function sendEmailToComplainant($complaintId)
    {

        $complaintObject = new Complaint;
        $complaintData = $complaintObject->getComplaintDataById($complaintId);
        
        try 
        {
            Mail::send(
                'backend.emails.complaintGenerated',
                [
                    'complaintNumber'       => Arr::get($complaintData, 'complaint_number'),
                    'orderId'               => Arr::get($complaintData, 'order_id'),
                    'queryType'             => config('constants.query_type.'.Arr::get($complaintData, 'query_type')),
                    'complaintType'         => config('constants.complaint_type.'.Arr::get($complaintData, 'complaint_type')),
                    'inquiryType'           => config('constants.inquiry_type.'.Arr::get($complaintData, 'inquiry_type')),
                    'name'                  => Arr::get($complaintData, 'name'),
                    'email'                 => Arr::get($complaintData, 'email'),
                    'mobileNumber'          => Arr::get($complaintData, 'mobile_number'),
                    'additionalComments'    => Arr::get($complaintData, 'comments'),
                    'app_url'               => URL::to('/'),
                ],
                function ($message) use ($complaintData) {
                    $message->to(trim(Arr::get($complaintData, 'email')));
                    $message->subject('Complaint Registered Successfully');
                }
            );

            //return true;
            \Log::info('Email sent successfully to ' . Arr::get($complaintData, 'email'));
        } catch (\Exception $e) 
        {
            \Log::error('Failed to send email: ' . $e->getMessage());
            return false;
        }

    }
}
