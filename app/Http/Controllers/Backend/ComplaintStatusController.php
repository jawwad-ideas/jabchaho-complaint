<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\ComplaintStatusCreateRequest;
use Illuminate\Support\Arr;
use App\Http\Requests\Backend\ComplaintStatusUpdateRequest;



class ComplaintStatusController extends Controller
{
    public function index()
    {
        $complaintStatusObject        = new ComplaintStatus;
        $complaintStatus              = $complaintStatusObject->select('*')->paginate(config('constants.per_page'));
        $complaintStatusCount         = $complaintStatus->count();
        $data['complaintStatus']      = $complaintStatus;
        $data['complaintStatusCount'] = $complaintStatusCount;
        return view('backend.complaint_status.index')->with($data);
    }

    public function destroy($complaintStatusId)
    {
        $complaintStatusObject = new ComplaintStatus;
        $deleted = $complaintStatusObject->deleteComplaintStatus($complaintStatusId);
        if($deleted)
        {
            return redirect()->back()->with('success', 'Complaint Status has been deleted successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }

    public function addComplaintStatusForm()
    {
        return view('backend.complaint_status.create');
    }

    public function create(ComplaintStatusCreateRequest $request)
    {
        $complaintStatusObject = new ComplaintStatus;
        $validateValues        = $request->validated();
        $name                  = Arr::get($validateValues,'name');
        $isEnabled             = Arr::get($validateValues,'is_enabled');

        $data = [
            'name' => $name,
            'is_enabled' => $isEnabled,
        ];

        $created = $complaintStatusObject->createComplaintStatus($data);
        if($created)
        {
            return redirect()->back()->with('success', 'Complaint Status has been created successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }
    }

    /**
     * Edit Complaint Status data
     * 
     * @param ComplaintStatus $complaintStatus
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(ComplaintStatus $complaintStatus) 
    {
        return view('backend.complaint_status.edit', [
            'complaintStatus' => $complaintStatus,

        ]);
    }

    public function update(ComplaintStatusUpdateRequest $request)
    {
        $complaintStatusObject = new ComplaintStatus;
        $complaintStatusId     = $request->input('complaintStatusId');
        $validateValues        = $request->validated();
        $name                  = Arr::get($validateValues,'name');
        $isEnabled             = Arr::get($validateValues,'is_enabled');

        $data = [
            'name' => $name,
            'is_enabled' => $isEnabled,
        ];

        $updated = $complaintStatusObject->updateComplaintStatus($data,$complaintStatusId);

        if($updated)
        {
            return redirect()->back()->with('success', 'Complaint Status has been updated successfully.');
        }
        else
        {
            return redirect()->back()->withErrors(['error' => "Whoops, looks like something went wrong."]);
        }

    }
}