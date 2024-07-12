<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\ComplainantSigninRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

class SigninController extends Controller
{
   /**
     * Display register Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function signinForm()
    {
        try
		{
            return view('frontend.auth.signin');
        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }	     
    }

    /**
     * Handle account registration request
     *
     * @param ComplainantSigninRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function signin(ComplainantSigninRequest $request)
    {
        try
		{
            $validateValues = $request->validated();
            
            $credentials = array(
                'email'     => Arr::get($validateValues, 'email'),
                'password'  => Arr::get($validateValues, 'password')
            );

            if (Auth::guard('complainant')->attempt($credentials))
            {
                if(!empty(Session::get('lastUrl'))){
                    return redirect(Session::get('lastUrl'));
                }else{
                    $complainantLandingRoute = config('constants.complainant_landing_route');
                    return redirect('/'.$complainantLandingRoute);
                }
                
            }
            return redirect()->action([SigninController::class , 'signin'])
            ->withInput()->withErrors(['error' => 'These credentials do not match our records.!']);
        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }	    
    }
}
