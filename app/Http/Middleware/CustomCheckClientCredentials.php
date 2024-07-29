<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class CustomCheckClientCredentials extends CheckClientCredentials
{
    public function handle($request, Closure $next, ...$scopes)
    {
        try {
            return parent::handle($request, $next, ...$scopes);
        } catch (AuthenticationException $exception) {
            // Custom response when client credentials fail
            $responsearray = array();
            $responsearray['status'] 	= false;
		    $responsearray['message']	= 'Unauthorized!!';  
            return response()->json($responsearray, 401);
        }
    }
}
