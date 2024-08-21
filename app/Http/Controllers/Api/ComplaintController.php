<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Models\Review;
use App\Http\Requests\Api\CreateComplaintRequest;
use App\Http\Requests\Api\TrackComplaintRequest;
use App\Http\Requests\Api\ReviewRequest;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Http\Traits\Configuration\ConfigurationTrait;
use App\Jobs\NotifyComplainant as NotifyComplainant;

class ComplaintController extends Controller
{
    use ConfigurationTrait;
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
            
            $insertData['device_type']          = Helper::getdevice($request); 
            $insertData['complaint_type']       = Arr::get($validateValues, 'complaint_type');   
            $insertData['order_id']             = Arr::get($validateValues, 'order_id');
            $insertData['service_id']           = Arr::get($validateValues, 'service_id');
            $insertData['name']                 = Arr::get($validateValues, 'name');
            $insertData['email']                = Arr::get($validateValues, 'email');
            $insertData['mobile_number']        = Arr::get($validateValues, 'mobile_number');
            $insertData['comments']             = Arr::get($validateValues, 'comments');
             
            
            $complaintData  = array();
            $complaintData  = Complaint::create($insertData);

            if(!empty($complaintData))
            { 
                $responseStatus 	        = true;

                $complaintId    = Arr::get($complaintData, 'id',0);

                $prefix =config('constants.complaint_number_starting_index'); //complaint_number_starting_index
                $complaintNumber = "JB-".($prefix + $complaintId)."-".date('Y');
    
                $complaintData->update(['complaint_number' => $complaintNumber]);
    
                //Files upload code
                $this->uploadImages($request,$complaintId);
                
                // Dispatch job to send emails and SMS
                dispatch(new NotifyComplainant($complaintId));
                $this->queueWorker();
                $responsearray['message'] 	        = 'Complaint Submitted Successfully';
            }
            else
            {
                $responsearray['message'] 	        = 'Error Submitting Complaint';
            }
            
            
            $responsearray['status'] 	        = $responseStatus;
        }
        catch(\Exception $e) 
        {
            $responsearray['message'] 	        = 'Error Submitting Complaint '.$e->getMessage();
            $responsearray['status'] 	        = false;

            // \Log::error("api/ComplaintController -> create =>".$e->getMessage());
        }
        return response()->json($responsearray);
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


    public function track(TrackComplaintRequest $request)
    {
        try
        {
            $responsearray                      = array();
            $responseStatus                     = false;
            $responseMessage                    = array();
            $complainData                       = array();
            
            $validateValues                     = $request->validated();

            $complaintNumber                    = Arr::get($validateValues, 'complaint_number');   
            
            $complaint =  Complaint::where(['complaint_number' =>$complaintNumber])->first();
            if(!empty($complaint))
            {
                //configuration filters
                $filters            = ['complaint_track_initiated','complaint_track_in_progress','complaint_track_completed'];
                    
                //get configurations
                $configurations     = $this->getConfigurations($filters);

                $complaintTrackInitiated  = explode(",", Arr::get($configurations, 'complaint_track_initiated'));
                $complaintTrackInProgress = explode(",",Arr::get($configurations, 'complaint_track_in_progress'));
                $complaintTrackCompleted  = explode(",",Arr::get($configurations, 'complaint_track_completed'));

                
                $status = config('constants.complaint_tracking_status.initiated');
                if(in_array(Arr::get($complaint, 'complaint_status_id'),$complaintTrackInProgress))
                {
                    $status = config('constants.complaint_tracking_status.in_progress');
                }
                else if(in_array(Arr::get($complaint, 'complaint_status_id'),$complaintTrackCompleted))
                {
                    $status = config('constants.complaint_tracking_status.completed');
                }
                
                $responseStatus                 = true;
                $responseMessage                = 'Successful';
                $complainData['order_id']       = Arr::get($complaint, 'order_id');
                $complainData['status']         = $status;
                $complainData['complaint_type'] = config('constants.complaint_type.'.Arr::get($complaint, 'complaint_type'));
                $complainData['service']        = Arr::get($complaint->service, 'name');
                $complainData['name']           = Arr::get($complaint, 'name');
                $complainData['email']          = Arr::get($complaint, 'email');
                $complainData['mobile_number']  = Arr::get($complaint, 'mobile_number');
                $complainData['comments']       = Arr::get($complaint, 'comments');
            } 
            else
            {
                $responseMessage                = 'No Complaint Found';
                
            }

            $responsearray['status'] 	        = $responseStatus;
            $responsearray['message'] 	        = $responseMessage;
            $responsearray['complainData'] 	    = $complainData;
        
            
            return response()->json($responsearray);
        }    
        catch(\Exception $e) 
        {
            \Log::error("api/ComplaintController -> track =>".$e->getMessage());
            return Helper::customErrorMessage();
        }
    }

    public function review(ReviewRequest $request)
    {
        try
        {
            $responsearray                          = array();
            $responseStatus                         = false;
            $responseMessage                        = array();

            $validateValues                         = $request->validated();
            
            $insertData['device_type']              = Helper::getdevice($request);  
            $insertData['order_id']                 = Arr::get($validateValues, 'order_id');
            $insertData['name']                     = Arr::get($validateValues, 'name');
            $insertData['email']                    = Arr::get($validateValues, 'email');
            $insertData['mobile_number']            = Arr::get($validateValues, 'mobile_number');
            $insertData['pricing_value']            = Arr::get($validateValues, 'pricing_value');
            $insertData['service_quality']          = Arr::get($validateValues, 'service_quality');
            $insertData['timelines_convenience']    = Arr::get($validateValues, 'timelines_convenience');
            $insertData['comments']                 = Arr::get($validateValues, 'comments');

            $reviewInserted  = array();
            $reviewInserted  = Review::create($insertData);

            if(!empty($reviewInserted))
            { 
                $responseStatus 	                = true;
                $responsearray['message'] 	        = 'Review Submitted Successfully';
            }
            else
            {
                $responsearray['message'] 	        = 'Error Submitting Review';
            }
            
            
            $responsearray['status'] 	        = $responseStatus;
        
            return response()->json($responsearray);
        }    
        catch(\Exception $e) 
        {
            \Log::error("api/ComplaintController -> review =>".$e->getMessage());
            return Helper::customErrorMessage();
        }
    }  
    
    public function getReviews()
    {
        try
        {
            $responsearray                          = array();
            $responseStatus                         = false;
            $responseMessage                        = array();

            
            $reviewObject = new Review();

            $reviews = $reviewObject->getReviewsByStatus(config('constants.review_statues_code.approved'));

            if(!empty($reviews))
            { 
                $responseStatus 	                = true;
                $responsearray['message'] 	        = 'Successful';
                $responsearray['reviews'] 	        = $reviews;
            }
            else
            {
                $responsearray['message'] 	        = 'No review Found!';
                $responsearray['reviews'] 	        = array();

            }

            $responsearray['status'] 	        = $responseStatus;
        
            return response()->json($responsearray);
        }    
        catch(\Exception $e) 
        {
            \Log::error("api/ComplaintController -> getReviews =>".$e->getMessage());
            return Helper::customErrorMessage();
        }
    }
}