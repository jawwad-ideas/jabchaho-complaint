<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintDocument;
use App\Models\Review;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Requests\Api\TrackComplaintRequest;
use App\Http\Requests\Api\ReviewRequest;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Http\Traits\Configuration\ConfigurationTrait;
use App\Jobs\NotifyComplainant as NotifyComplainant;

class OrderController extends Controller
{
    use ConfigurationTrait;
    /**
     * create Order.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(CreateOrderRequest $request)
    {
        $validateValues                     = $request->validated();
        $responseStatus                     = true;
        try
        {
            $responsearray                      = array();
            $responsearray['status'] 	        = $responseStatus;
            $responsearray['message'] 	        = 'Order Successfully Created';
        }
        catch(\Exception $e) {
            $responsearray['message'] 	        = 'Error Submitting Complaint '.$e->getMessage();
            $responsearray['status'] 	        = false;
        }

        //$responsearray['request']           = $validateValues;//json_decode($request->getContent(),true);
        return response()->json($responsearray);
    }
}
