<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ComplaintsExport;
use App\Exports\ReportByComplaintsExport;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\AssignToRequest;
use App\Models\ComplaintDocument;
use Illuminate\Support\Arr;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Complainant;
use App\Models\ComplaintStatus;
use App\Models\ComplaintFollowUp;
use App\Http\Requests\Backend\ComplaintFollowUpRequest;
use App\Http\Requests\Backend\ReAssignRequest;
use App\Models\ComplaintPriority;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\AssignedComplaint as AssignedComplaint;
use App\Jobs\ComplaintStatusChanged as ComplaintStatusChanged; 
use App\Http\Traits\Configuration\ConfigurationTrait;
use App\Models\ComplaintAssignedHistory as ComplaintAssignedHistory;
use App\Http\Requests\StoreComplaintRequest;

class ComplaintController extends Controller
{
    use ConfigurationTrait;

    public function index(Request $request)
    {
        $data = $filterData = array();

        $query                    = Complaint::select('*');
        $objectComplaintStatus    = new ComplaintStatus;
        
        // Apply filters
        $complaint_number           = $request->input('complaint_number');
        $order_id                   = $request->input('order_id');
        $mobile_number              = $request->input('mobile_number');
        $name                       = $request->input('name');
        $email                      = $request->input('email');
        $complaint_status_id        = $request->input('complaint_status_id');

        //complaint_number condition
        if (!empty($complaint_number)) 
        {
            $query->where('complaints.complaint_number', 'like', '%' . $complaint_number . '%');
        }

        if (!empty($order_id))
        {
            $query->where('complaints.order_id','like', '%' .$order_id. '%');
        }

        if (!empty($mobile_number))
        {
            $query->where('complaints.mobile_number','like', '%' .$mobile_number. '%');
        }

        if (!empty($name))
        {
            $query->where('complaints.name','like', '%' .$name. '%');
        }

        if (!empty($email))
        {
            $query->where('complaints.email','like', '%' .$email. '%');
        }

        if (!empty($complaint_status_id))
        {
            $query->where('complaints.complaint_status_id','=', $complaint_status_id);
        }
        
 
        $filterData = [
            'complaint_number'      => $complaint_number,
            'order_id'              => $order_id,
            'mobile_number'         => $mobile_number,
            'name'                  => $name,
            'email'                 => $email,
            'complaint_status_id'   => $complaint_status_id

        ];
        
        if(!Auth::user()->hasRole(config('constants.roles.admin')) && !Auth::user()->hasRole(config('constants.roles.complaint_management_team')))
        {
            $userId= Auth::guard('web')->user()->id;
            $query = $query->where(['user_id' =>$userId]);
        }

        $complaints = $query->orderBy('id', 'DESC')->paginate(config('constants.per_page'));
        
        $data['complaints']             = $complaints;
        $data['complaintStatuses']      = $objectComplaintStatus->getComplaintStatuses();
        
        return view('backend.complaints.index')->with($data)->with($filterData);
    }


    public function destroy($complaintId)
    {
        
        //remove complaint documents
        $complaintDocuments= ComplaintDocument::where(['complaint_id'=> $complaintId])->get();
        if ($complaintDocuments->isNotEmpty())
        {
            
            foreach($complaintDocuments as $complaintDocument)
            {
               //file name
               $fileName = Arr::get($complaintDocument, 'file');

               if(!empty($fileName))
               {
                    //folder path
                    $uploadFolderPath = config('constants.files.complaint_documents');

                    //file path
                    $filePath               = public_path($uploadFolderPath);

                    // file name with path
                    $fileNameWithPath = $filePath . '/' . $fileName;

                    //remove File
                    Helper::removeFile($fileNameWithPath);
               }

               $complaintDocument->delete();
            }
        }
        
        ComplaintFollowUp::where(['complaint_id'=> $complaintId])->delete();
        $deleted = Complaint::where(['id'=> $complaintId])->delete();
        if($deleted)
        {
            return redirect()->route('complaints.index')->with('success', 'Complaint has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);

        }

    }

    public function approve($complaintId)
    {

        $complaint = Complaint::where(['id'=>$complaintId]);
        $updated = $complaint->update([
            'is_approved' => 1,
            'approved_by_user_id' => auth()->id()
        ]);
        if($updated)
        {
            return redirect()->back()->with('success', 'Complaint id '.$complaint->first()->complaint_num.' has been Approved successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }

    public function assignComplaintForm(Request $request)
    {
        $complaintId = $request->input('complaintId');

        $complaintObject                = new Complaint;
        $complaintData                  = $complaintObject->getComplaintDataById($complaintId);
        $users                          = User::get()->toArray();
        $complaintPriorities            = ComplaintPriority::get()->toArray();

        $data['complaintPriorities']    = $complaintPriorities;
        $data['complaintData']          = $complaintData;
        $data['users']                  = $users;

        return view('backend.complaints.assign_complaint_form')->with($data);

    }

    public function assignComplaint(AssignToRequest $request)
    {
        $params = array();
        
        $complaintId            = $request->input('complaintId');;
        $userId                 = $request->input('userId');

        $params['complaintId']  = $complaintId;
        $params['priorityId']   = $request->input('priorityId');
        $params['userId']       = $userId;

        $complaint              = new Complaint;

        $assigned               = $complaint->assignTo($params);

        if($assigned)
        {
            //add history
            $historyData = array();

            $historyData['complaint_id']            = $complaintId;
            $historyData['complaint_priority_id']   = $request->input('priorityId');
            $historyData['assigned_to']             = $userId;
            $historyData['assigned_by']             = auth()->id();

            if($userId != auth()->id())
            {
                ComplaintAssignedHistory::insert($historyData);
            }
            
            // Dispatch job to send emails
            dispatch(new AssignedComplaint($complaintId,$userId));
            $this->queueWorker();

            return response()->json(['status' => true, 'message'=>"Complaint has been assigned successfully."]);
        }
        else
        {
            return response()->json(['status' =>false, 'message'=> "Whoops, looks like something went wrong."]);
        }
    }

    public function show(Request $request)
    {

        $complaintObject                    = new Complaint;
        $complaintDocumentObject            = new ComplaintDocument;
        $objectComplaintFollowUp            = new ComplaintFollowUp;
        $objectComplaintAssignedHistory     = new ComplaintAssignedHistory;

        $complaintId        = $request->route('complaintId');
        $complaintData      = $complaintObject->getComplaintDataById($complaintId);
        if(!empty($complaintData))
        {
            $complaintDocument  = $complaintDocumentObject->getComplaintDocumentById($complaintId);

            //Full data of complaint
            $data['complaintData']                  = $complaintData;
            $data['complaintDocument']              = $complaintDocument;
            $data['complaintFollowUps']             = $objectComplaintFollowUp->getComplaintFollowUps($complaintId);
            $data['complaintAssignedHistory']       = $objectComplaintAssignedHistory->getComplaintAssignedHistory($complaintId);

            return view('backend.complaints.show')->with($data);
        }
        else
        {
            return redirect()->route('complaints.index')->withErrors(['error' => "Invalid Complaint"]);
        }
        
    }

    
    /**
     * Folow up data
     *
     * @param Complaint $complaint
     *
     * @return \Illuminate\Http\Response
     */
    public function followUp(Complaint $complaint)
    {
        $data           = array();

        $objectComplaintStatus    = new ComplaintStatus;
        $objectComplaintFollowUp  = new ComplaintFollowUp;

        $complaintId = Arr::get($complaint,'id');

        $data['complaintStatuses']       = $objectComplaintStatus->getComplaintStatuses();
        $data['complaint']               = $complaint;
        $data['complaintFollowUps']      = $objectComplaintFollowUp->getComplaintFollowUps($complaintId);
        return view('backend.complaints.follow_up')->with($data);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComplaintFollowUp  $complaintFollowUp
     * @return \Illuminate\Http\Response
     */
    public function followUpDestroy(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            $complaintFollowUpId = (int) $request->segment(4);

            ComplaintFollowUp::where(['id'=>$complaintFollowUpId])->delete();
            return redirect()->back()->with('success', "Description Removed Successfully.");
        }
        else
        {
            return redirect()->back()
            ->withErrors("Sorry you don't have permission to remove");
        }


    }



    /**
     * comment on the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function followUpSaved(ComplaintFollowUpRequest $request, Complaint $complaint){

        $validateValues = $request->validated();

        $complaintStatusId  = Arr::get($validateValues, 'complaint_status_id');
        $description      = Arr::get($validateValues, 'description');
        $complaintId        = Arr::get($complaint, 'id');



        //Comments add
        $followUp = ComplaintFollowUp::create([
            'complaint_status_id'       => $complaintStatusId,
            'description'               => $description,
            'created_by'                => auth()->id(),
            'complaint_id'              => $complaintId,
        ]);

        //update status on job table

        $complaint->update([
            'complaint_status_id' => $complaintStatusId,
            'updated_by' => auth()->id()
        ]);

        //configuration filters
        $filters            = ['complaint_sms_api_enable','complaint_sms_action','complaint_sms_sender','complaint_sms_username','complaint_sms_password','complaint_sms_format','complaint_sms_api_url','complaint_status_changed_sms_template','complaint_status_id','complaint_status_notify_type'];
        
        //get configurations
        $configurations     = $this->getConfigurations($filters);

        if(in_array($complaintStatusId, explode(',',Arr::get($configurations, 'complaint_status_id'))))
        {
            // Dispatch job to send emails
            dispatch(new ComplaintStatusChanged($complaintId,$complaintStatusId,$configurations));
            $this->queueWorker();
        }

        return redirect()->back()->with('success', "Description Added successfully.");
    }


     /**
     * Show form for creating user
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        
        $users                          = User::get()->toArray();
        $complaintPriorities            = ComplaintPriority::get()->toArray();

        
        $data['complaintTypes']          = config('constants.complaint_type'); 
        $data['services']                = config('constants.services'); 
        $data['complaintPriorities']    = $complaintPriorities;
        $data['users']                  = $users;
        
        return view('backend.complaints.create')->with($data);
    }

    public function store(StoreComplaintRequest $request)
    {
        $validated = $request->validated();

        // Process the complaint here
    }



}
