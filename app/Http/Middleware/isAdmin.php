<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        $cek = auth()->user()->role_id;
        if ($cek != 'pegawai') {
            return $next($request);
        }
        abort(401, 'Bukan akses Anda');

    }
}
