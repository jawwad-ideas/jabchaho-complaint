<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\ComplaintBaseController;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use App\Models\ComplaintFollowUp;
use App\Http\Requests\Frontend\StoreComplaintRequest;


class ComplaintController extends ComplaintBaseController
{
    public function index()
    {
        $data = array();
        $complainantId= Auth::guard('complainant')->user()->id;
        
        $complaintObject  =  new Complaint();
        $complaints = $complaintObject->getComplaintsByComplainantId($complainantId);
        $data['complaints']  = $complaints;

        return view('frontend.complainants.complaints.index')->with($data);
    }

    public function show($id)
    {
        $complaintData = Complaint::findOrFail($id);
        $objectComplaintFollowUp  = new ComplaintFollowUp;
        $data['complaintData'] = $complaintData;
        $data['complaintFollowUps'] = $objectComplaintFollowUp->getComplaintIsNotifyFollowUps($id);
        return view('frontend.complainants.complaints.show')->with($data);
    }

    public function create(Request $request)
    {
        $roleHaveAccess = false;
        $data = parent::create($request);
        $data['roleHaveAccess']         = $roleHaveAccess;   
        $data['storeUrl']               = route('complaints.store');
        $data['redirectUrl']            = route('complaints'); 
        return view('frontend.complainants.complaints.create')->with($data);
    }
    
    public function store(StoreComplaintRequest $request)
    {
        $this->complainantId= Auth::guard('complainant')->user()->id;
        return parent::store($request);
    }


    public function unsetcomplaintFilesSession()
    {
        \Session::forget('complaintFiles');
    }
}
