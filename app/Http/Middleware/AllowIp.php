<?php

namespace App\Http\Middleware;

use Closure;
use App\WhiteListIp;
use App\User;

class AllowIp
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
        $end1 = \Carbon\Carbon::create(2021, 8, 8, 23, 00, 0, 'Asia/Makassar');
        $now = \Carbon\Carbon::now();
        // dd(request());
        $server = request()->server();
        // dd($server);
        $ip = request()->ip();
        $whitelist = WhiteListIp::pluck('ip')->contains($ip);
        $user = auth()->id();
        $pimpinan = User::where('role_id', '<>', 'pegawai')->pluck('id')->contains($user);
        // dd($user);
        $trim = \Str::substr($ip, 0, 5);
        $trim2 = \Str::substr($ip, 0, 4);
        // if(today()->isSaturday() && $pimpinan) {

        //     return $next($request);
        // }

        // if (today()->isSaturday()) {
        //     return $next($request);
        // }else{
        //     if ($whitelist) {
        //     // if ($trim == '10.28' || $trim2 == '10.1' || $trim2 == '125.' || $trim2 == '10.0' || $trim2 == '114.') {
        //         return $next($request);
        //     }
        // }

        // if ($whitelist) {
        //         return $next($request);
        //     }

        // abort(504, 'Anda terdeteksi menggunakan jaringan luar '
        //             .config("app.name").'. Gunakan jaringan dalam '.config("app.name").'.');

        if ($now > $end1) {
            if ($whitelist) {
            // if ($trim == '10.28' || $trim2 == '10.1' || $trim2 == '125.' || $trim2 == '10.0' || $trim2 == '114.') {
                return $next($request);
            }

            abort(504, 'Anda terdeteksi menggunakan jaringan luar '
                    .config("app.name").'. Gunakan jaringan dalam '.config("app.name").'.');
            // return redirect()->route('oot');
        }else{
            return $next($request);
        }

    }
}
