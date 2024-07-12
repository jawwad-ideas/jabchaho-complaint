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
            if(Auth::user()->hasRole('admin'))
            {
                $data['mnaList'] = $userObject->getUsersWithRole(3);//role_id = 3 = mna
                $data['mpaList'] = $userObject->getUsersWithRole(4);//role_id = 4 = mpa
            }
            return view('backend.home.index')->with($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);

        }
    }

    /**
     * Handle Graph data Request request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    function getComplaintsGraphData(Request $request)
    {
        try
        {
            $dataset                        = array();
            $year                           = date('Y');

            if(!empty($request->get('year')))
            {
                $year  = $request->get('year');
            }

            $complaintObject                = new Complaint();

            #complaints
            $params['year']                 = $year;
            $complaints                     = $complaintObject->complaintsRespectToYearAndStatus($params);
            $dataset = array(
                [
                    'label'             => 'Complaints',
                    'backgroundColor'   => "#9966ff",
                    'data'              => Helper::monthDataMergeWithDefaultMonthArray($complaints)
                ],
            );

            return response()->json($dataset);
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
            $complaintStatusCount = array();
            $userId = Auth::guard('web')->user()->id;
            $customStartDate = $request->get('customStartDate');
            $customEndDate = $request->get('customEndDate');
            //if filters are clicked 
            if(!empty($request->get('filterValue')) || !empty($request->get('userId')) || (!empty($customStartDate) && !empty($customEndDate)))
            {
                $filterValue                 = $request->get('filterValue');

                if (!is_null($request->get('filterValue'))){

                    $datesArray                  = Helper::getDateByFilterValue($filterValue);

                    $startDate                   = Arr::get($datesArray, 'startDate');
                    $endDate                     = Arr::get($datesArray, 'endDate');

                    $usersCount                  = User::whereBetween('created_at', [$startDate, $endDate])->count();
                    $complainantsCount           = Complainant::whereBetween('created_at', [$startDate, $endDate])->count();

                    $params['startDate']         = $startDate;
                    $params['endDate']           = $endDate;

                }else{
                    $usersCount                  = User::count();
                    $complainantsCount           = Complainant::count();
                }

                //dashboard for admin
                if(Auth::user()->hasRole('admin'))
                {
                
                    $params['userType']         = 'user_id';
                    if (!is_null($request->get('userId'))){
                        $params['userId']            = $request->get('userId');
                        $params['userType']            = $request->get('usertype');
                    }
                    if(!is_null($request->get('customStartDate')) && !is_null($request->get('customEndDate')))
                    {
                        $params['customStartDate']            = $request->get('customStartDate');
                        $params['customEndDate']              = $request->get('customEndDate');
                    }
                    
                    $complaints                  = $complaintObject->complaintCount($params);
                    $complaintStatusCount        = $complaintObject->complaintStatusCount($params);
                    
                    if($request->get('usertype') == 'mpa_id'){
                        //fetch data mpa wise with respect to complaints with filter dates
                        $mpaComplaintAreaWise        = $complaintObject->mpaComplaintCount($params);
                        //fetch data mna wise with respect to complaints with filter dates
                        $mnaComplaintAreaWise        = [];
                    }elseif ($request->get('usertype') == 'user_id') {
                        //fetch data mpa wise with respect to complaints with filter dates
                        $mpaComplaintAreaWise        = [];
                        //fetch data mna wise with respect to complaints with filter dates
                        $mnaComplaintAreaWise        =  $complaintObject->mnaComplaintCount($params);
                    }else{
                        //fetch data mpa wise with respect to complaints with filter dates
                        $mpaComplaintAreaWise        = $complaintObject->mpaComplaintCount($params);
                        //fetch data mna wise with respect to complaints with filter dates
                        $mnaComplaintAreaWise        = $complaintObject->mnaComplaintCount($params);
                    }

                    

                    //fetch data category wise with respect to complaints with filter dates
                    $categoryComplaintAreaWise   = $complaintObject->categoriesComplaintCount($params);
                }
                //dashboard for mna
                elseif(Auth::user()->hasRole('MNA'))
                {
                    if(!is_null($request->get('customStartDate')) && !is_null($request->get('customEndDate')))
                    {
                        $params['customStartDate']            = $request->get('customStartDate');
                        $params['customEndDate']              = $request->get('customEndDate');
                    }

                    $params['userId']            = $userId;
                    $params['userType']            = 'user_id';
                    $complaints                  = $complaintObject->complaintCount($params);
                    $complaintStatusCount        = $complaintObject->complaintStatusCount($params);
                    $mpaComplaintAreaWise        = [];
                    $mnaComplaintAreaWise        = $complaintObject->mnaComplaintCount($params);
                    $categoryComplaintAreaWise   = $complaintObject->categoriesComplaintCount($params);
                }
                //dashboard for mpa
                elseif (Auth::user()->hasRole('MPA'))
                {
                    if(!is_null($request->get('customStartDate')) && !is_null($request->get('customEndDate')))
                    {
                        $params['customStartDate']            = $request->get('customStartDate');
                        $params['customEndDate']              = $request->get('customEndDate');
                    }
                    $params['userId']            = $userId;
                    $params['userType']            = 'mpa_id';
                    $complaints                  = $complaintObject->complaintCount($params);
                    $complaintStatusCount        = $complaintObject->complaintStatusCount($params);
                    $mpaComplaintAreaWise        = $complaintObject->mpaComplaintCount($params);
                    $mnaComplaintAreaWise        = [];
                    $categoryComplaintAreaWise   = $complaintObject->categoriesComplaintCount($params);
                }
                //fetch data areas wise with respect to complaints
                $areasWiseComplaint              = $complaintObject->areasComplaintCount($params);
            }
            //default view before filters are clicked
            else
            {
                $usersCount                 = User::count();
                $complainantsCount          = Complainant::count();

                if(Auth::user()->hasRole('admin'))
                {
                    $params['userType']         = 'user_id';
                    $complaints                 = $complaintObject->complaintCount($params);
                    //fetch data mpa wise with respect to complaints
                    $mpaComplaintAreaWise       = $complaintObject->mpaComplaintCount($params);
                    //fetch data mna wise with respect to complaints
                    $mnaComplaintAreaWise       = $complaintObject->mnaComplaintCount($params);
                    //fetch data category wise with respect to complaints
                    $categoryComplaintAreaWise  = $complaintObject->categoriesComplaintCount($params);

                    $complaintStatusCount       = $complaintObject->complaintStatusCount($params);
                }
                elseif(Auth::user()->hasRole('MNA'))
                {
                    
                    $params['userId']           = $userId;
                    $params['userType']         = 'user_id';
                    $complaints                 = $complaintObject->complaintCount($params);
                    $mnaComplaintAreaWise       = $complaintObject->mnaComplaintCount($params);
                    $mpaComplaintAreaWise       = [];
                    $categoryComplaintAreaWise  = $complaintObject->categoriesComplaintCount($params);
                    $complaintStatusCount       = $complaintObject->complaintStatusCount($params);
                }
                elseif(Auth::user()->hasRole('MPA'))
                {

                    $params['userId']           = $userId;
                    $params['userType']         = 'mpa_id';
                    $complaints                 = $complaintObject->complaintCount($params);
                    $mnaComplaintAreaWise       = [];
                    $mpaComplaintAreaWise       = $complaintObject->mpaComplaintCount($params);
                    $categoryComplaintAreaWise  = $complaintObject->categoriesComplaintCount($params);
                    $complaintStatusCount       = $complaintObject->complaintStatusCount($params);
                }
                //fetch data areas wise with respect to complaints
                $areasWiseComplaint             = $complaintObject->areasComplaintCount();
            }

            $data['users']                  = $usersCount;
            $data['complainants']           = $complainantsCount;
            $data['complaintStatus']        = $complaintStatusCount;
            //mpaComplaintCount
            $data['mpaComplaintsCount']     = $mpaComplaintAreaWise;
            //mnaComplaintCount
            $data['mnaComplaintsCount']     = $mnaComplaintAreaWise;
            
            //categoryComplaintCount
            $data['categoryComplaintCount'] = $categoryComplaintAreaWise;
            //areaComplaintCount
            $data['areasComplaintCount']    = $areasWiseComplaint;
            $data['complaints']             = $complaints;
            $data['hasRole']                = Auth::user()->getRoleNames();

            $data =  array_merge($data,$complaintStatusCount);

            return response()->json($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);

        }
    }

}
