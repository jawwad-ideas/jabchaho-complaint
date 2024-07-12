<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\ComplainantResetPasswordRequest;
use Carbon\Carbon;
use App\Models\Complainant;
use App\Models\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{

    /**

     * Reset Password form.

     *

     * @return \Illuminate\Http\Response

     */
    public function resetPasswordForm($token=''){

        try
		{
			$currentDateTimeWithSubDays = Carbon::now()->subDays(env('PASSWORD_RESET_TOKEN_EXPIRE_IN_DAYS'));

			$resetPasswordToken = PasswordReset::select('token')->where(['token'=>$token])
			->whereDate('updated_at', '>=', $currentDateTimeWithSubDays)
			->first();
			if(!empty($resetPasswordToken)){

                $data =array();
                $data['token'] = $token;
                return view('frontend.auth.passwords.reset')->with($data);

            }else{

                return redirect('/signin')->withInput()->withErrors(['error' =>'Invalid reset password token!!']);
            }    


            
        }catch(\Exception $e)
		{
			return $this->getCustomExceptionMessage($e);
        }	

    }

    /**
     * Handle account registration request
     *
     * @param ComplainantResetPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function resetPassword(ComplainantResetPasswordRequest $request){

        $errorMessage       = '';
        $token      		= $request->input('token');
        $password   		= $request->input('password');
        $currentDateTimeWithSubDays 	= Carbon::now()->subDays(env('PASSWORD_RESET_TOKEN_EXPIRE_IN_DAYS'));

        $resetPasswordToken = PasswordReset::select('complainant_id')->where(['token'=>$token])
        ->whereDate('updated_at', '>=', $currentDateTimeWithSubDays)
        ->first();

        if(!empty($resetPasswordToken))
        {
            $complainantId = Arr::get($resetPasswordToken, 'complainant_id',0);
            $input['password']      	= Hash::make($password);
            
            $complainant = Complainant::where(['id' => $complainantId])->update($input);

            if($complainant)
            {
                PasswordReset::where(['token'=>$token])->delete();

                return redirect('/login')->with('success', "Password reset successfully!");
            }else{
                $errorMessage	= 'Password reset failed. Please try again!';
            }
        }
        else{
                $errorMessage     =  'Invalid reset password token!!';
        }    

        return redirect('/login')->withInput()->withErrors(['error' =>$errorMessage]);
    }
}
