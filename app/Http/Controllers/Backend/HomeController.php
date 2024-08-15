<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\ComplaintStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        try
        {
            $userObject = new User();
            $data = $params = array();
            return view('backend.home.index')->with($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);

        }
    }

    
    /**
     * Count data Request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    function getCountData(Request $request)
    {
        try
        {
            $data = $params = array();

            $complaintObject       = new Complaint;

            $complaintStatusObject = new ComplaintStatus;

            $filterValue = '';
            $applicantStatusCount = array();
            if(!empty($request->get('filterValue')))
            {
                $filterValue  = $request->get('filterValue');
                
                $datesArray = Helper::getDateByFilterValue($filterValue);
 
                $startDate              = Arr::get($datesArray, 'startDate');
                $endDate                = Arr::get($datesArray, 'endDate');

                $params['startDate']    = $startDate;
                $params['endDate']      = $endDate;
                               
            }
            else if(!empty($request->get('customStartDate')) && !empty($request->get('customEndDate')))
            {   
                $customStartDate        = date("Y-m-d 00:00:00", strtotime($request->get('customStartDate')));
                $customEndDate          = date("Y-m-d 23:59:59", strtotime($request->get('customEndDate')));

                $params['startDate']    = $customStartDate;
                $params['endDate']      = $customEndDate;
            }


            $complaints                  = $complaintObject->complaintCount($params);
            $complaintStatusCount        = $complaintObject->complaintStatusCount($params);
            $complaintCountByService     = $complaintObject->getComplaintCountByService($params);

            $data['complaints']                 = $complaints;
            $data['complaintStatus']            = $complaintStatusCount;
            $data['complaintCountByService']    = $complaintCountByService;
           
           $data =  array_merge($data,$complaintStatusCount);

            return response()->json($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }
    }

}
