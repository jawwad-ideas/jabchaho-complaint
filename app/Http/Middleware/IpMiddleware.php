<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\Configuration\ConfigurationTrait;
use Illuminate\Support\Arr;

class IpMiddleware
{
    use ConfigurationTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        //configuration filters
        $filters            = ['api_ips_whitelist'];
            
        //get configurations
        $configurations     = $this->getConfigurations($filters);
        
        $ips                = Arr::get($configurations, 'api_ips_whitelist');
    
        $ipsArray =  explode(',',$ips);
        if (in_array(request()->server('SERVER_ADDR'), $ipsArray)) {
            return $next($request);
        }
        
        $responsearray = array();
        $responsearray['status'] 	= false;
        $responsearray['message']	= 'Unauthorized Access';  
        return response()->json($responsearray, 403);
        //abort(403);
    }
}
