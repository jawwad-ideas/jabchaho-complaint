<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Backend\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\RememberMeExpiration;

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

        return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        $redirectUrl = 'home.index';
        $permissionIndexPagesArray = array();
        
        //get all users permission
        if(!empty($user->getAllPermissions()))
        {
            //check .index and list of .index
            foreach($user->getAllPermissions() as $row)
            {
                if(str_contains($row->name, '.index'))
                {
                    $permissionIndexPagesArray[] = $row->name;
                }
            }
        }

        //get list of .index permisson
        if(!empty($permissionIndexPagesArray))
        {
            //check if cms.index exist then url move to cms.index
            if(in_array('home.index',$permissionIndexPagesArray))
            {
                $redirectUrl = 'home.index';
            }
            elseif(reset($permissionIndexPagesArray)) //first .index url
            {
                $redirectUrl = reset($permissionIndexPagesArray);
            }
        }
        
    
        return redirect(route($redirectUrl));
    }
}
