<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Helpers\Helper;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
       
        if (! is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        }

        if ( is_null($permission) ) {
            $permission = $request->route()->getName();

            $permissions = array($permission);
        }
        
        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
            else{
                /*
                 * check if user login and hit domain [https://complaint.jabchaho.com] 
                 * donot have segment1 permission then redirect to first .index route permission
                 */
    
                $segment1 = $request->segment(1);
                if (app('auth')->check() && app('auth')->user() && empty($segment1))
                { 
                    return Helper::authenticated(app('auth')->user());
                }
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}