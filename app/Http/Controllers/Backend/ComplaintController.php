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

class ComplaintController extends Controller
{
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
             // Dispatch job to send emails and SMS
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

        $complaintObject            = new Complaint;
        $complaintDocumentObject    = new ComplaintDocument;
        $objectComplaintFollowUp  = new ComplaintFollowUp;
        $complaintId        = $request->route('complaintId');
        $complaintData      = $complaintObject->getComplaintDataById($complaintId);
        if(!empty($complaintData))
        {
            $complaintDocument  = $complaintDocumentObject->getComplaintDocumentById($complaintId);

            //Full data of complaint
            $data['complaintData']      = $complaintData;
            $data['complaintDocument']  = $complaintDocument;
            $data['complaintFollowUps'] = $objectComplaintFollowUp->getComplaintFollowUps($complaintId);

            return view('backend.complaints.show')->with($data);
        }
        else
        {
            return redirect()->route('complaints.index')->withErrors(['error' => "Invalid Complaint"]);
        }
        
    }

    

    public function report_index(Request $request)
    {
            // Initialize the query
        $complaints = Complaint::query();
        $filtersApplied = false;
        $categoryObject = new Category();

        // Apply filters based on request input
        if ($request->filled('start_date')) {
            $complaints->where('created_at', '>=', $request->input('start_date'));
            $filtersApplied = true;
        }

        if ($request->filled('end_date')) {
            $complaints->where('created_at', '<=', $request->input('end_date'));
            $filtersApplied = true;
        }

        if($request->filled('period')){
            $filterValue = $request->input('period');
            $datesArray  = Helper::getDateByFilterValue($filterValue);
            $startDate   = Arr::get($datesArray, 'startDate');
            $endDate     = Arr::get($datesArray, 'endDate');
            $complaints  = $complaints->whereBetween('created_at', [$startDate, $endDate]);
            $filtersApplied = true;
        }

        if ($request->filled('cnic')) {
            $complaints->whereHas('complainant', function ($query) use ($request) {
                $query->where('cnic', 'like', '%' .$request->input('cnic').'%');
            });
            $filtersApplied = true;
        }

        if ($request->filled('mobile_number')) {
            $complaints->whereHas('complainant', function ($query) use ($request) {
                $query->where('mobile_number', 'like', '%' .$request->input('mobile_number'). '%');
            });
            $filtersApplied = true;
        }

        if ($request->filled('level_one')) {
            if(is_array($request->input('level_one'))){
                $complaints->whereIn('level_one', $request->input('level_one'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",",$request->input('level_one'));
                $complaints->whereIn('level_one', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($request->filled('level_two')) {
            if(is_array($request->input('level_two'))){
                $complaints->whereIn('level_two', $request->input('level_two'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",",$request->input('level_two'));
                $complaints->whereIn('level_two', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($request->filled('level_three')) {
            if(is_array($request->input('level_three'))){
                $complaints->whereIn('level_three', $request->input('level_three'));
                $filtersApplied = true;
            }else{
                $explodedArray = explode(",",$request->input('level_three'));
                $complaints->whereIn('level_three', $explodedArray);
                $filtersApplied = true;
            }
        }

        if ($request->filled('title')) {
            $complaints->where('title', $request->input('title'));
            $filtersApplied = true;
        }

        if ($request->filled('complaint_status_id')) {
            $complaints->where('complaint_status_id', $request->input('complaint_status_id'));
            $filtersApplied = true;
        }

        if ($request->filled('city_id')) {
            $complaints->where('city_id', $request->input('city_id'));
            $filtersApplied = true;
        }

        if ($request->filled('new_area_id')) {
            $complaints->where('new_area_id', $request->input('new_area_id'));
            $filtersApplied = true;
        }

        if ($request->filled('district_id')) {
            $complaints->where('district_id', $request->input('district_id'));
            $filtersApplied = true;
        }

        if ($request->filled('sub_division_id')) {
            $complaints->where('sub_division_id', $request->input('sub_division_id'));
            $filtersApplied = true;
        }

        if ($request->filled('union_council_id')) {
            $complaints->where('union_council_id', $request->input('union_council_id'));
            $filtersApplied = true;
        }

        if ($request->filled('charge_id')) {
            $complaints->where('charge_id', $request->input('charge_id'));
            $filtersApplied = true;
        }

        if ($request->filled('ward_id')) {
            $complaints->where('ward_id', $request->input('ward_id'));
            $filtersApplied = true;
        }

        if ($request->filled('provincial_assembly_id')) {
            $complaints->where('provincial_assembly_id', $request->input('provincial_assembly_id'));
            $filtersApplied = true;
        }

        if ($request->filled('national_assembly_id')) {
            $complaints->where('national_assembly_id', $request->input('national_assembly_id'));
            $filtersApplied = true;
        }

        if ($request->filled('mna_id')) {
            $complaints->where('user_id', $request->input('mna_id'));
            $filtersApplied = true;
        }

        if ($request->filled('mpa_id')) {
            $complaints->where('mpa_id', $request->input('mpa_id'));
            $filtersApplied = true;
        }

        if ($request->filled('complaint_approved_id')) {
            $complaints->where('is_approved', $request->input('complaint_approved_id'));
            $filtersApplied = true;
        }


        // If no filters are applied, return an empty collection
        if (!$filtersApplied) {
            $data['counts'] = null;
        } else {
            // Get the results with pagination and append the current query parameters
            $counts = (object) $complaints->selectRaw('
                COUNT(*) AS total,
                COUNT(CASE WHEN complaint_status_id = 1 THEN 1 END) AS complaint_registered,
                COUNT(CASE WHEN complaint_status_id = 2 THEN 1 END) AS in_process,
                COUNT(CASE WHEN complaint_status_id = 3 THEN 1 END) AS hold,
                COUNT(CASE WHEN complaint_status_id = 4 THEN 1 END) AS resolved,
                COUNT(CASE WHEN complaint_status_id = 5 THEN 1 END) AS closed,
                COUNT(CASE WHEN is_approved = 1 THEN 1 END) AS approved,
                COUNT(CASE WHEN is_approved = 0 THEN 1 END) AS pending_approval,
                COUNT(CASE WHEN city_id = 1 THEN 1 END) AS karachi_complaints,
                COUNT(CASE WHEN city_id = 2 THEN 1 END) AS hyderabad_complaints');

            // $data['complaints'] = $complaints->paginate(config('constants.per_page'))->appends($request->query());
            $data['counts']     = $counts->get();
        }

        // Fetch other necessary data for the filters
        // $data['nic'] = Complainant::getUniqueNicNumbers();
        // $data['phoneNumber'] = Complainant::getUniquePhoneNumbers();
        // $data['levelOne'] = $categoryObject->getFirstLevel();
        // $data['levelTwo'] = $categoryObject->getSecondLevel();
        // $data['levelThree'] = $categoryObject->getThirdLevel();
        // $data['titles'] = Complaint::pluck('title')->whereNull('deleted_at');
        // $data['cities'] = City::all()->whereNull('deleted_at');
        // $data['newAreas'] = NewArea::all()->whereNull('deleted_at');
        // $data['districts'] = District::all()->whereNull('deleted_at');
        // $data['divisions'] = SubDivision::all()->whereNull('deleted_at');
        // $data['ucs'] = UnionCouncil::all()->whereNull('deleted_at');
        // $data['charges'] = Charge::all()->whereNull('deleted_at');
        // $data['wards'] = Ward::all()->whereNull('deleted_at');
        // $data['pas'] = ProvincialAssembly::all()->whereNull('deleted_at');
        // $data['nas'] = NationalAssembly::all()->whereNull('deleted_at');
        $complaintStatusObject = new ComplaintStatus;
        $userObject = new User;
        $data['statuses'] = $complaintStatusObject->getComplaintStatuses()->whereNull('deleted_at');
        $data['mnaList'] = $userObject->getUsersWithRole(3);//role_id = 3 = mna
        $data['mpaList'] = $userObject->getUsersWithRole(4);//role_id = 4 = mpa

        if ($request->input('export') == 'excel') {
            return Excel::download(new ComplaintsExport($request), 'complaints.xlsx');
        }

        return view('backend.reports.complains')->with($data);
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

        return redirect()->back()->with('success', "Description Added successfully.");
    }

    public function trackComplaint(Request $request)
    {
        // Fetch complaints
        $complaints   = Complaint::select('*');

        // Apply filters
        $complaint_number   = $request->input('complaint_number');
        $cnic               = $request->input('cnic');
        $mobile_number      = $request->input('mobile_number');

        if (!empty($complaint_number)) {
            $complaints->where('complaints.complaint_num', 'like', '%' . $complaint_number . '%');
        }

        if (!empty($cnic)) {
            $complaints->whereHas('complainant', function ($query) use ($cnic) {
                $query->where('cnic', 'like', '%' . $cnic . '%');});
        }

        if (!empty($mobile_number)) {
            $complaints->whereHas('complainant', function ($query) use ($mobile_number) {
                $query->where('mobile_number', 'like', '%' . $mobile_number . '%');});
        }

        $filterData = [
            'complaint_number' => $complaint_number,
            'cnic'             => $cnic,
            'mobile_number'    => $mobile_number,
        ];

        $userWise = true;
        if(Auth::user()->hasRole('admin'))
        {
            $userWise = false;
        }
        else if(Auth::user()->hasRole('call-center'))
        {
            $userWise = false;
        }

        // Paginate the results
        if($userWise)
        {
            $userId= Auth::guard('web')->user()->id;
            $query = $complaints->where(['user_id' =>$userId]);
        }

        $complaintsResult   = $complaints->orderBy('id', 'DESC')->paginate(config('constants.per_page'));

        // Fetch cities, districts, and complaint numbers
        $cities = City::all(); // Assuming you have a City model and table
        $districts = District::all(); // Assuming you have a District model and table

        $categoryObject     = new Category;
        $levelOneCategory   = $categoryObject->getFirstLevel();
        $levelTwoCategory   = $categoryObject->getSecondLevel();
        $levelThreeCategory = $categoryObject->getThirdLevel();

        $complaintStatusIds    = ComplaintStatus::all();

        $data = [
            'levelOneCategory'   => $levelOneCategory,
            'levelTwoCategory'   => $levelTwoCategory,
            'levelThreeCategory' => $levelThreeCategory,
            'filterData'         => $filterData,
            'cities'             => $cities,
            'districts'          => $districts,
            'complaintStatusIds' => $complaintStatusIds,
        ];
        $data['complaints']   = $complaintsResult;

        return view('backend.tracking.track_complaint')->with($data);
    }

    public function reAssignComplaintForm(Request $request)
    {
        $complaintId = $request->input('complaintId');

        $complaintObject                = new Complaint;
        $complaintData                  = $complaintObject->getComplaintDataById($complaintId);
        $userObject = new User();
        $data = $params = array();

        $data['mnaList'] = $userObject->getUsersWithRole(3);//role_id = 3 = mna
        $data['mpaList'] = $userObject->getUsersWithRole(4);//role_id = 4 = mpa
        $data['complaintData']          = $complaintData;

        return view('backend.complaints.re_assign_to_form')->with($data);

    }

    public function reAssignComplaint(ReAssignRequest $request)
    {
        $params = array();
        $params['complaintId']  = $request->input('complaintId');
        $params['mnaId']       = $request->input('mnaId');
        $params['mpaId']       = $request->input('mpaId');

        $complaint      = new Complaint;

        $reAssigned       = $complaint->reAssignTo($params);

        if($reAssigned)
        {
            return response()->json(['status' => true, 'message'=>"Complaint has been re-assigned successfully."]);
        }
        else
        {
            return response()->json(['status' =>false, 'message'=> "Whoops, looks like something went wrong."]);
        }
    }

    public function reportByComplaints(Request $request)
    {
            // Initialize the query
        $complaints = Complaint::query();
        $filtersApplied = false;
        $categoryObject = new Category();

        // Apply filters based on request input
        if ($request->filled('start_date')) {
            $complaints->where('created_at', '>=', $request->input('start_date'));
            $filtersApplied = true;
        }

        if ($request->filled('end_date')) {
            $complaints->where('created_at', '<=', $request->input('end_date'));
            $filtersApplied = true;
        }

        if ($request->filled('cnic')) {
            $complaints->whereHas('complainant', function ($query) use ($request) {
                $query->where('cnic', 'like', '%' .$request->input('cnic').'%');
            });
            $filtersApplied = true;
        }

        if ($request->filled('mobile_number')) {
            $complaints->whereHas('complainant', function ($query) use ($request) {
                $query->where('mobile_number', 'like', '%' .$request->input('mobile_number'). '%');
            });
            $filtersApplied = true;
        }

        if ($request->filled('level_one')) {
            $complaints->where('level_one', $request->input('level_one'));
            $filtersApplied = true;
        }

        if ($request->filled('level_two')) {
            $complaints->where('level_two', $request->input('level_two'));
            $filtersApplied = true;
        }

        if ($request->filled('level_three')) {
            $complaints->where('level_three', $request->input('level_three'));
            $filtersApplied = true;
        }

        if ($request->filled('title')) {
            $complaints->where('title', $request->input('title'));
            $filtersApplied = true;
        }

        if ($request->filled('complaint_status_id')) {
            $complaints->where('complaint_status_id', $request->input('complaint_status_id'));
            $filtersApplied = true;
        }

        if ($request->filled('city_id')) {
            $complaints->where('city_id', $request->input('city_id'));
            $filtersApplied = true;
        }

        if ($request->filled('new_area_id')) {
            $complaints->where('new_area_id', $request->input('new_area_id'));
            $filtersApplied = true;
        }

        if ($request->filled('district_id')) {
            $complaints->where('district_id', $request->input('district_id'));
            $filtersApplied = true;
        }

        if ($request->filled('sub_division_id')) {
            $complaints->where('sub_division_id', $request->input('sub_division_id'));
            $filtersApplied = true;
        }

        if ($request->filled('union_council_id')) {
            $complaints->where('union_council_id', $request->input('union_council_id'));
            $filtersApplied = true;
        }

        if ($request->filled('charge_id')) {
            $complaints->where('charge_id', $request->input('charge_id'));
            $filtersApplied = true;
        }

        if ($request->filled('ward_id')) {
            $complaints->where('ward_id', $request->input('ward_id'));
            $filtersApplied = true;
        }

        if ($request->filled('provincial_assembly_id')) {
            $complaints->where('provincial_assembly_id', $request->input('provincial_assembly_id'));
            $filtersApplied = true;
        }

        if ($request->filled('national_assembly_id')) {
            $complaints->where('national_assembly_id', $request->input('national_assembly_id'));
            $filtersApplied = true;
        }

        // If no filters are applied, return an empty collection
        if (!$filtersApplied) {
            $data['complaints'] = collect();
        } else {
            // Get the results with pagination and append the current query parameters
            $data['complaints'] = $complaints->paginate(config('constants.report_per_page'))->appends($request->query());
        }

        // Fetch other necessary data for the filters
        $data['nic'] = Complainant::getUniqueNicNumbers();
        $data['phoneNumber'] = Complainant::getUniquePhoneNumbers();
        $data['levelOne'] = $categoryObject->getFirstLevel();
        $data['levelTwo'] = $categoryObject->getSecondLevel();
        $data['levelThree'] = $categoryObject->getThirdLevel();
        $data['titles'] = Complaint::pluck('title')->whereNull('deleted_at');
        $data['cities'] = City::all()->whereNull('deleted_at');
        $data['newAreas'] = NewArea::all()->whereNull('deleted_at');
        $data['districts'] = District::all()->whereNull('deleted_at');
        $data['divisions'] = SubDivision::all()->whereNull('deleted_at');
        $data['ucs'] = UnionCouncil::all()->whereNull('deleted_at');
        $data['charges'] = Charge::all()->whereNull('deleted_at');
        $data['wards'] = Ward::all()->whereNull('deleted_at');
        $data['pas'] = ProvincialAssembly::all()->whereNull('deleted_at');
        $data['nas'] = NationalAssembly::all()->whereNull('deleted_at');
        $complaintStatusObject = new ComplaintStatus;
        $data['statuses'] = $complaintStatusObject->getComplaintStatuses()->whereNull('deleted_at');

        if ($request->input('export') == 'excel') {
            return Excel::download(new ReportByComplaintsExport($request), 'complaints.xlsx');
        }

        return view('backend.reports.details')->with($data);
    }
}
