<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Helper;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Helper::checkGoogle2FaIsenabled())
        {
            $user = Auth::user();

            // Only enforce 2FA if it is enabled
            if ($user && $user->google2fa_enabled && !$request->session()->has('2fa_verified')) {
                return redirect()->route('google2fa.setup')->withErrors(['error' => "You must set up 2FA first."]);
            }
        }

        return $next($request);
    }
}
