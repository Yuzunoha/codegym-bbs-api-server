<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->merge(['c' => 'd']);

        return $next($request);
    }
}
