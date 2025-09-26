<?php

namespace App\Http\Middleware;

use Closure;
use Http;

class Validation
{
    /**
     * Handle an incoming request.
     * Rahmat Suregar
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
