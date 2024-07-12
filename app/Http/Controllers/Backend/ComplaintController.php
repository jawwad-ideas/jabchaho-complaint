<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ComplaintsExport;
use App\Exports\ReportByComplaintsExport;
use App\Http\Controllers\ComplaintBaseController;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\AssignToRequest;
use App\Models\ComplaintDocument;
use App\Models\City;
use App\Models\District;
use App\Models\Category;
use App\Http\Requests\Frontend\StoreComplaintRequest;
use Illuminate\Support\Arr;
use App\Helpers\Helper;
use App\Models\Complainant;
use App\Models\ComplaintStatus;
use App\Models\ComplaintFollowUp;
use App\Http\Requests\Backend\ComplaintFollowUpRequest;
use App\Http\Requests\Backend\ReAssignRequest;
use App\Models\Charge;
use App\Models\ComplaintPriority;
use App\Models\NationalAssembly;
use App\Models\NewArea;
use App\Models\ProvincialAssembly;
use App\Models\SubDivision;
use App\Models\UnionCouncil;
use App\Models\UserWiseAreaMapping;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ComplaintController extends ComplaintBaseController
{
    public function index(Request $request)
    {
        // Fetch complaints
        $complaints   = Complaint::select('complaints.*','complainants.mobile_number', 'complainants.cnic','users.name as approved_by')->join('complainants', 'complaints.complainant_id', '=', 'complainants.id');
        $complaints->leftJoin('users','users.id','=','complaints.approved_by_user_id');
        $dashboardFilter                = $request->query('dashboard_filter');
        $userId                         = Auth::guard('web')->user()->id;
        $pendingComplaints = Complaint::select("*")->where('complaint_status_id',  1);



        if(!empty($dashboardFilter))
        {
            $datesArray = Helper::getDateByFilterValue($dashboardFilter);

            $startDate              = Arr::get($datesArray, 'startDate');
            $endDate                = Arr::get($datesArray, 'endDate');

            $complaints->whereBetween('complaints.created_at', [$startDate, $endDate]);
        }

        // Apply filters
        $complaint_number = $request->input('complaint_number');
        $mobile_number = $request->input('mobile_number');
        $cnic = $request->input('cnic');
        $title            = $request->input('title');
        $levelOne         = $request->input('level_one');
        $levelTwo         = $request->input('level_two');
        $levelThree       = $request->input('level_three');
        $city             = $request->input('city_id');
        $district         = $request->input('district_id');
        $status           = $request->input('complaint_status_id');
        $approvalStatus           = $request->input('complaint_approved_id');
        //die($approvalStatus);
        $created_at_from           = $request->input('created_at_from');
        $created_at_to           = $request->input('created_at_to');

        if (!empty($complaint_number))
        {
            $complaints->where('complaints.complaint_num', 'like', '%' . $complaint_number . '%');
        }

        if (!empty($mobile_number))
        {
            $complaints->where('complainants.mobile_number','like', '%' .$mobile_number. '%');
        }

        if (!empty($cnic))
        {
            $complaints->where('complainants.cnic', 'like', '%' . $cnic. '%');
        }

        if (!empty($title)) {
            $complaints->where('complaints.title', 'like', '%' . $title . '%');
        }

        if (!empty($levelOne)) {
            $complaints->where('complaints.level_one', $levelOne);
        }

        if (!empty($levelTwo)) {
            $complaints->where('complaints.level_two', $levelTwo);
        }

        if (!empty($levelThree)) {
            $complaints->where('complaints.level_three', $levelThree);
        }

        if (!empty($city)) {
            $complaints->where('complaints.city_id', $city);
        }

        if (!empty($district)) {
            $complaints->where('complaints.district_id', $district);
        }

        if (!empty($status)) {
            $complaints->where('complaints.complaint_status_id', $status);
        }
        if (!empty($approvalStatus)) {
            $flag = ($approvalStatus == 'yes') ? 1 : 0;
            $complaints->where('complaints.is_approved', $flag);
        }

        if ($created_at_from) {
            $created_at_from .= ' 00:00:00';
            $complaints->where('complaints.created_at', '>=', $created_at_from);

            if ($created_at_to) {
                $created_at_to .= ' 23:59:59';
                $complaints->where('complaints.created_at', '<=', $created_at_to);
            }
          }

        $filterData = [
            'complaint_number'    => $complaint_number,
            'mobile_number'    => $mobile_number,
            'cnic'    => $cnic,
            'title'               => $title,
            'level_one'           => $levelOne,
            'level_two'           => $levelTwo,
            'level_three'         => $levelThree,
            'city'                => $city,
            'district'            => $district,
            'complaint_status_id' => $status,
            'complaint_approved_id' => $approvalStatus,
            'created_at_from' => $created_at_from,
            'created_at_to' => $created_at_to,
        ];

        $userWise = true;
        if(Auth::user()->hasRole('admin'))
        {
            $userWise = false;
            $pendingComplaints->where('is_approved', 0);
        }

        if(Auth::user()->hasRole('call-center'))
        {
            $userWise = false;
            $complaints = $complaints->where(['complaints.created_by' =>$userId]);
            $pendingComplaints->where('created_by', $userId);
        }

        if(Auth::user()->hasRole('Manager'))
        {
            $userWise = false;
            $complaints = $complaints->where(['complaints.is_approved' =>0]);
            $pendingComplaints->where('is_approved', 0);
        }
        // Paginate the results
        if($userWise)
        {
            if(Auth::user()->hasRole('MPA')){
                $complaints = $complaints->where(['complaints.mpa_id' =>$userId,'complaints.is_approved' =>1]);
                $pendingComplaints->where('is_approved', 1);
                $pendingComplaints->where('mpa_id', $userId);
            }
            if(Auth::user()->hasRole('MNA')){
                $complaints = $complaints->where(['complaints.user_id' =>$userId,'complaints.is_approved' =>1]);
                $pendingComplaints->where('is_approved', 1);
                $pendingComplaints->where('user_id', $userId);

            }


        }

        $complaintsResult = $complaints->orderBy('complaints.id', 'DESC')->paginate(config('constants.per_page'));


        //dd($complaints->toRawSql());
        // Fetch cities, districts, and complaint numbers
        $cities = City::all(); // Assuming you have a City model and table
        $districts = District::all(); // Assuming you have a District model and table

        $categoryObject     = new Category;
        $levelOneCategory   = $categoryObject->getFirstLevel();
        $levelTwoCategory   = $categoryObject->getSecondLevel();
        $levelThreeCategory = $categoryObject->getThirdLevel();

        $complaintStatusIds    = ComplaintStatus::all();
        //die($pendingComplaints->toRawSql());
        $data = [
            'levelOneCategory'   => $levelOneCategory,
            'levelTwoCategory'   => $levelTwoCategory,
            'levelThreeCategory' => $levelThreeCategory,
            'filterData'         => $filterData,
            'cities'             => $cities,
            'districts'          => $districts,
            'complaintStatusIds' => $complaintStatusIds,
            'pendingComplaints' => $pendingComplaints->count()
        ];

        $filterData['dashboardFilter']          = $dashboardFilter;

        $data['complaints'] = $complaintsResult;

        return view('backend.complaints.index')->with($data)->with($filterData);
    }


    public function destroy($complaintId)
    {
        $complaint  = new Complaint;
        $deleted    = $complaint->deleteComplaint($complaintId);

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
        $complaintPriorities            = ComplaintPriority::get()->toArray();

        $data['complaintPriorities']    = $complaintPriorities;
        $data['complaintData']          = $complaintData;

        return view('backend.complaints.assign_complaint_form')->with($data);

    }

    public function assignComplaint(AssignToRequest $request)
    {
        $params = array();
        $params['complaintId']  = $request->input('complaintId');
        $params['priorityId']   = $request->input('priorityId');

        $complaint      = new Complaint;

        $assigned       = $complaint->assignTo($params);

        if($assigned)
        {
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
        $complaintDocument  = $complaintDocumentObject->getComplaintDocumentById($complaintId);

        //Full data of complaint
        $data['complaintData']      = $complaintData;
        $data['complaintDocument']  = $complaintDocument;
        $data['complaintFollowUps'] = $objectComplaintFollowUp->getComplaintFollowUps($complaintId);
        $data['refrence_detail'] = json_decode($complaintData->extras, true);

        return view('backend.complaints.show')->with($data);
    }

    public function create(Request $request)
    {
        $roleHaveAccess = true;
        $data = parent::create($request);
        $data['roleHaveAccess']         = $roleHaveAccess;
        $data['storeUrl']               = route('rolebase.complaints.store');
        $data['redirectUrl']            = route('complaints.index');
        return view('backend.complaints.create')->with($data);
    }

    public function store(StoreComplaintRequest $request)
    {
        //Create account
        $validateValues = $request->validated();

        $email                          = Arr::get($validateValues,'email');
        $cnic                           = Arr::get($validateValues,'cnic');
        $mobileNumber                   = Arr::get($validateValues,'mobile_number');
        $password                       = Helper::generateAlphaNumeric(10);

        $newComplainantData['full_name']       = Arr::get($validateValues,'full_name');
        $newComplainantData['gender']          = Arr::get($validateValues,'gender');
        $newComplainantData['cnic']            = $cnic;
        $newComplainantData['email']           = $email;
        $newComplainantData['mobile_number']   = $mobileNumber;
        $newComplainantData['password']        = $password;

        $complainant                    = Complainant::findByEmailOrCnicOrPhoneNo($email, $cnic, $mobileNumber);

        if ($complainant)
        {
            $complainantId              = Arr::get($complainant,'id');

        }
        else
        {
            $complainant                = Complainant::create($newComplainantData);
            $complainantId              = $complainant->id;
            $this->sendEmailToNewCreatedComplainant($newComplainantData);
        }


        $this->complainantId            = $complainantId;
        $this->complaintPriorityId      = Arr::get($validateValues,'priorityId');
        $this->userId                   = Arr::get($validateValues,'userId');

        unset($validateValues['roleHaveAccess']);
        unset($validateValues['full_name']);
        unset($validateValues['gender']);
        unset($validateValues['cnic']);
        unset($validateValues['email']);
        unset($validateValues['userId']);
        unset($validateValues['priorityId']);



        return parent::store($request);
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
        $isNotify = $request->get('notify_customer') ? 1 : 0;
        $description      = Arr::get($validateValues, 'description');
        $complaintId        = Arr::get($complaint, 'id');



        //Comments add
        $followUp = ComplaintFollowUp::create([
            'complaint_status_id'       => $complaintStatusId,
            'description'               => $description,
            'is_notify'               => $isNotify,
            'created_by'                => auth()->id(),
            'complaint_id'              => $complaintId,
        ]);

        //update status on job table

        $complaint->update([
            'complaint_status_id' => $complaintStatusId,
            'updated_by' => auth()->id()
        ]);

        if($isNotify){
            $newComplainantData= [];
            $objectComplaintFollowUp  = new ComplaintFollowUp;
            $followUpDetail = $objectComplaintFollowUp->getNotifyFollowUp($followUp->id);
            $userDetails = $complaint->getComplaintsWithComplainant($complaintId);
            $newComplainantData['full_name']       = Arr::get($userDetails,'full_name');
            $newComplainantData['email']       = Arr::get($userDetails,'email');
            $newComplainantData['complainId']          = Arr::get($followUpDetail,'complaint_id');
            $newComplainantData['description']            = Arr::get($followUpDetail,'description');
            $this->sendEmailToIsNotifyCustomer($newComplainantData);
        }

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

    public function getMNADetails(Request $request)
    {
        $mnaId = $request->input('mnaId');

        // Fetch all MNAs matching the user_id
        $mnas = UserWiseAreaMapping::where('user_id', $mnaId)->get();

        if ($mnas->isNotEmpty()) {
            $mnaDetails = $mnas->map(function ($mna) {
                return [
                    'provincial_assembly_id' => $mna->provincial_assembly_id,
                    'national_assembly_id' => $mna->national_assembly_id,
                ];
            });
            return response()->json($mnaDetails);
        } else {
            return response()->json(['error' => 'No MNAs found for the given user_id'], 404);
        }
    }

    public function getMnaWiseMpa(Request $request)
    {
        $userObject = new User;
        $mnaId = $request->input('mnaId');
        $provincialAssemblyId = $request->input('provincialAssemblyId');
        $nationalAssemblyId = $request->input('nationalAssemblyId');

        $mnaWiseMpa = $userObject->getMnaWiseMpa($mnaId, $provincialAssemblyId, $nationalAssemblyId);

        return response()->json(['mpaList' => $mnaWiseMpa]);
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
