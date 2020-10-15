<?php

namespace App\Http\Middleware;

use Closure;

class Verify
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
        if ( $request->user('api')->isVerified === 0 ) {
            return response(['error' => 'Your Email has not yet been verified', 'status' => false], 403);
        }
        return $next($request);
    }
}
