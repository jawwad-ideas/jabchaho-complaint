<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsByUser;

class ReportController extends Controller
{
    public function getReportByUser(Request $request)
    {
        try
        {
            $complaintObject    = new Complaint;
            $complaintStatusObject = new ComplaintStatus;
            $complaintStatuses = $complaintStatusObject->getComplaintStatuses();
            $result             = $complaintObject->getComplaintByUserReport($request,$complaintStatuses);

            if ($request->input('export') == 'excel') 
            {
                return Excel::download(new ReportsByUser($result), 'complaints_reports_by_user.xlsx');
            }
            $data['complaintStatuses']       = $complaintStatuses;

            return view('backend.reports.user_complaint_counts', $result)->with($data);
            
        }
        catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
        }
    }

}
