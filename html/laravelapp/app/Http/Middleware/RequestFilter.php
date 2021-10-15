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
        if ($request->per_page && ctype_digit($request->per_page)) {
            /* per_pageの指定があり、かつそれが十進数である */
            $per_page = intval($request->per_page);
        } else {
            /* per_pageの指定がない、またはあっても十進数でない */
            $per_page = 20;
        }

        $request->merge(['per_page' => $per_page]);
        return $next($request);
    }
}
