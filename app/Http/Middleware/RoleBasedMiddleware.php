<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('complainant')->check()) 
        {
            // Authorized as a complainant, proceed
            return $next($request);
        } elseif (auth()->guard('web')->check()) {
            // Authorized user with permission, proceed
            return $next($request);
        } else {
            // Not authorized, redirect or throw exception
            return response()->json([
                'message' => 'You are not authorized to access this resource. Please refresh the page and try again.',
            ], 401);
            // OR throw new UnauthorizedHttpException('You are not authorized to access this route.');
        }
    }
}
