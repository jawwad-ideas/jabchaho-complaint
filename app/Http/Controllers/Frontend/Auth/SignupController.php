<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Backend\ComplaintController;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Complainant;
use App\Http\Requests\Frontend\ComplainantSignupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\Helper;
class SignupController extends Controller
{
    /**
     * Display signup Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupForm()
    {

        try
		{
            $data = array();
            $data['genderOptions'] = config('constants.gender_options');
            return view('frontend.auth.signup')->with($data);

        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }
    }

    /**
     * Handle account registration request
     *
     * @param ComplainantSignupRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function signup(ComplainantSignupRequest $request)
    {
        try
		{

            $validateValues = $request->validated();
            if (!empty($validateValues))
            {
                $complainant = Complainant::create($validateValues);
                //sign up email sent
                $complaint = new ComplaintController;
                $complaint->sendEmailToNewCreatedComplainant($validateValues);
                Auth::guard('complainant')->login($complainant);

                $complainantLandingRoute = config('constants.complainant_landing_route');
                return redirect('/'.$complainantLandingRoute)->with('success', "Account successfully Created.");

            }
            else
            {
                return redirect()->action([SignupController::class , 'signup'])
                    ->withErrors(['error' => "Whoops, looks like something went wrong."]);

            }
        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }

    }

    #refresh captacha
    public function refreshCaptcha()
    {
        try
        {
            return response()->json(['captcha' => captcha_img() ]);

        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
        }
    }

}
