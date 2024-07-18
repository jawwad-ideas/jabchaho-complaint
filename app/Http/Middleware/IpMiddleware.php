<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        $ips = env('API_IP_WHITELIST');
        $ipsArray =  explode(',',$ips);
        if (in_array($request->ip(), $ipsArray)) {
            return $next($request);
        }
        
        $responsearray = array();
        $responsearray['status'] 	= false;
        $responsearray['message']	= 'Unauthorized Access';  
        return response()->json($responsearray, 403);
        //abort(403);
    }
}
