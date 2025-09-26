<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class Trial
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

        // $set = \App\Pengaturan::first();
        // // dd($set);
        // $url = 'https://hadir.rsuregar.my.id/api/validate/'.$set->keygen.'/'.request()->getHost().'';
        // $response = \Http::withoutVerifying()->get($url)->object();

        // dd($url);

        // return response()->json($response);
        // if (!$response) {
        //     abort(503, 'Tidak dapat memvalidasi aplikasi. Kemungkinan terjadi kesalahan pada Serial Key. Silakan melakukan pembelian.');
        // }else{
        //         $exp = new Carbon($response->valid_until);
        //         $now = \Carbon\Carbon::now();

        //         if ($response->confirmed) {
        //             \Illuminate\Support\Facades\View::share('exp', $exp);
        //             \Illuminate\Support\Facades\View::share('confirmed', $response->confirmed);
        //             \Illuminate\Support\Facades\View::share('ok', $response->confirmed);
        //         }else{
        //             if($now > $exp){
        //                 abort(503, 'Masa percobaan aplikasi telah berakhir. Silakan melakukan pembelian aplikasi full version');
        //             }
        //             \Illuminate\Support\Facades\View::share('exp', $exp);
        //             \Illuminate\Support\Facades\View::share('confirmed', $response->confirmed);
        //             \Illuminate\Support\Facades\View::share('ok', $response->confirmed);
        //         }

                  return $next($request);
        // }
    }
}
