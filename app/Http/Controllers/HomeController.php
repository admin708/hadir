<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cek = auth()->user()->role_id;

        // return $cek;

        // if (!$cek == 'admin') {
        if ($cek == 'pegawai' || $cek == 'pimpinan' || $cek == 'pimpinan_umum') {
            $all_jadwal = auth()->user()->unitkerja->all_jadwal;
            $set = \App\Pengaturan::first();
            $faq = \App\Faq::whereIsShow(true)->get();
            $pulang_cepat = request()->pulang_cepat;

            if (request()->isMethod('post')) {
                $jadwal_id = auth()->user()->update(['jadwal_id' => request()->jadwal_id]);
            }
             $jadwal = \App\Jadwal::getJadwalPegawai(auth()->user()->jadwal_id, auth()->id());
            //  dd($jadwal);
            // return view('App.index', [
            //     'set' => $set,
            //     'faq' => $faq,
            //     'jadwal' => $jadwal,
            //     'pulang_cepat' => $pulang_cepat,
            //     'all_jadwal' => $all_jadwal
            // ]);
            return view('AppNew.index');
        }

        return redirect()->route('presensi.index');
        // return view('home', ['data' => $jadwal]);
        // return $jadwal;
    }
}
