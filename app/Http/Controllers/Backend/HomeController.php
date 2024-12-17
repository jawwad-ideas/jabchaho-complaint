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
use App\Models\Order;
use App\Models\OrderItemImage;
use App\Models\OrderItemIssue;

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
            $complaintCountByUser        = $complaintObject->getComplaintCountByUsers();

            $data['complaints']                 = $complaints;
            $data['complaintStatus']            = $complaintStatusCount;
            $data['complaintCountByService']    = $complaintCountByService;
            $data['complaintCountByUser']       = $complaintCountByUser;
           
           $data =  array_merge($data,$complaintStatusCount);

            return response()->json($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }
    }


    public function jabchahoDashboard()
    {
        try
        {
            $data = $params = array();
            return view('backend.home.jabchaho-dashboard')->with($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);

        }
    }

    /**
     * jabchaho Dashboard Counts Request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    function getJabchahoDashboardCountData(Request $request)
    {
        try
        {
            $data = $params = array();
            $filterValue = '';

            $orderObject                 = new Order;
            $orderItemImageObject        = new OrderItemImage; //
            $orderItemIssueObject        = new OrderItemIssue;

            
            $page = $request->query('page'); // Default to page 1 if not provided
            $limit = $request->query('limit'); // Default to 10 items per page if not provided

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


            if(!empty($request->get('name')))
            {
                $params['name']    = $request->get('name');
            }

            if(!empty($request->get('telephone')))
            {
                $params['telephone']    = $request->get('telephone');
            }

            if(!empty($request->get('orderNumber')))
            {
                $params['orderNumber']    = $request->get('orderNumber');
            }
            //locationType
            if(!empty($request->get('locationType')))
            {
                $params['locationType']    = $request->get('locationType');
            }

            //issueType
            if(!empty($request->get('issueType')))
            {
                $params['issueType']    = $request->get('issueType');
            }

            $params['page']                     = $page;
            $params['limit']                    = $limit;

            $orders                             = $orderObject->orderCount($params);
            $orderItemImage                     = $orderItemImageObject->orderItemImagesCount($params);
            $orderItemIssuesWithCount           = $orderItemIssueObject->getItemissuesCount($params);

            $itemIssueWithCount = [];
            $itemIssueWithCountArray = [];

            if(!empty($orderItemIssuesWithCount))
            {
                foreach($orderItemIssuesWithCount as $row)
                {
                    $itemIssueWithCount[str_replace(" ", "_", config('constants.issues.'.Arr::get($row, 'issue')))]     = Arr::get($row,'count');

                }
            }

            $ordersWithItemsCountData           = $orderObject->getOrdersWithItemCount($params);
            $ordersWithItemsCount               = Arr::get($ordersWithItemsCountData, 'orders');
            $orderWithItemTotalCount            = Arr::get($ordersWithItemsCountData, 'totalRecords');
            
            $data['orders']                     = $orders;
            $data['orderItemImage']             = $orderItemImage;
            $data['ordersWithItemsCount']       = $ordersWithItemsCount;
            $data['orderWithItemTotalCount']    = $orderWithItemTotalCount;
            $data['itemIssueWithCount']         = $itemIssueWithCount;
            
            return response()->json($data);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
            
        }
    }


}
