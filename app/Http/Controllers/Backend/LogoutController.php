<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function perform()
    {
        //Session::flush();
        
        session()->forget(['2fa_required', '2fa_verified']);
        Auth::logout();

        return redirect()->guest(route('login.perform'));
        //return redirect(config('constants.admin_url_prefix').'/login');
    }
}
