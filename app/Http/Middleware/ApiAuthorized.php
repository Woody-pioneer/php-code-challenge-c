<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $api_key = $request->input('api_key');//default:12345678
        if ($api_key !== env('API_KEY')) {
            return response()->json(['error' => "API key or Parameters  not correct"], 500);
        }
        return $next($request);
    }
}
