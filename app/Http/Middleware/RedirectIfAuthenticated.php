<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                $segment1      = $request->segment(1);
                $segment2      = $request->segment(2);

                $adminUrlPrefix = config('constants.admin_url_prefix');  

                if($adminUrlPrefix != $segment1.'/'.$segment2 )
                {
                    return redirect(RouteServiceProvider::HOME);
                }
               

            }
        }

        return $next($request);
    }
}
