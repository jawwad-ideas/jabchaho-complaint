<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Requests\Frontend\ComplainantForgotPasswordRequest;
use App\Models\Complainant;
use App\Models\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
class ForgotPasswordController extends Controller
{
    /**

     * Forget Password form.

     *

     * @return \Illuminate\Http\Response

     */

    public function forgotPasswordForm()
    {

        try
		{ 
            return view('frontend.auth.passwords.forgot');
        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
        }	
        
    }

    /**
     * Handle account registration request
     *
     * @param ComplainantForgotPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function forgotPassword(ComplainantForgotPasswordRequest $request)
    {
        try
		{
            $errorMessage = '';
            $email      		    = $request->input('email');
            $complainantData        = Complainant::where('email', $email)->first();

            if(empty($complainantData))
            {
                $errorMessage	= 'Sorry, we were unable to locate this email in our database.';
            }
            else
            { 
                $token 				    = Helper::generateAlphaNumeric(64).time();
                $complainantId          = Arr::get($complainantData, 'id',0);
                if(!empty($complainantId))
                {
                    $token = $token.".".$complainantId;
                }

                $complainant    = PasswordReset::updateOrCreate( ['complainant_id' => $complainantId],['token'=>$token]);
                if($complainant)
                {
                    Mail::send('frontend.emails.forgotPassword', ['token' => $token,'fullName'=>$complainantData['full_name']], function($message) use($request){
                        $message->to($request->email);
                        $message->subject('Reset Password');
                    });

                    return redirect('/forgot-password')->with('success', "We have e-mailed your password reset link!");
                }else{
                    $errorMessage  = 'Unable to password reset. Please try again!';
                }
            }    

            return redirect('/forgot-password')->withInput()->withErrors(['error'=> $errorMessage]);


        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }	    
    }
}
