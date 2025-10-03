<?php

namespace App\Http\Middleware;

use Closure;

class NewDomain
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
        $host = request()->getHost();
        if ($host === 'hadir.test') {
            return $next($request);
        }else{
            return redirect()->route('new-domain');
            // dd('download aplikasi terbaru disini');
        }

    }
}
