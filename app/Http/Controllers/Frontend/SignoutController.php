<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SignoutController extends Controller
{
    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function signout()
    {
        try
		{ 
            //Session::flush();
            Session::forget('lastUrl');
            Auth::guard('complainant')
                ->logout();
            return redirect()
                ->guest(route('signin.show'));
        }catch(\Exception $e)
        {
            return $this->getCustomExceptionMessage($e);
        }        
    }
}
