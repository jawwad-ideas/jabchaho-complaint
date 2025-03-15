<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Backend\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\RememberMeExpiration;
use App\Helpers\Helper;

class LoginController extends Controller
{
    use RememberMeExpiration;

    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('backend.auth.login');
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
            return redirect()->to(config('constants.admin_url_prefix').'/login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user, $request->get('remember'));

        if($request->get('remember')):
            $this->setRememberMeExpiration($user);
        endif;

        if(Helper::checkGoogle2FaIsenabled())
        {
            if ($user->google2fa_enabled) 
            {
                // Store 2FA required state in session
                session(['2fa_required' => true]);
        
                // Redirect to 2FA verification page
                return redirect()->to('google2fa/setup');
            }
        }
        
        //return $this->authenticated($request, $user);
        return Helper::authenticated($user);
    }

   
}
