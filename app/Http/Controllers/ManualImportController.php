<?php

namespace App\Http\Controllers;

use App\Hadir;
use App\Presensi;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Jadwal;

class ManualImportController extends Controller
{
    public function create(Request $request)
    {
        if (!\Gate::denies('pegawai')) {
            abort(401);
        }
        return view('Imports.index', ['ok' => true]);
    }

    public function store(Request $request)
    {

        // return $request->all();
        if (!\Gate::denies('pegawai')) {
            abort(401);
        }

        $data = collect();
        try {
            foreach ($request->user_id as $key => $value) {
                $user = User::find($value);
                $jadwal = Jadwal::getJadwalPegawai($user->jadwal_id);
                $toleransi = $jadwal->late_tolerance;
                $clock_out = $jadwal->clock_out_time;

                // return $jadwal;

                $masuk = Presensi::whereTipe(1)->updateOrCreate(
                    [
                        "user_id" => $value,
                        'tanggal' => Carbon::parse($request->masuk)->format('Y-m-d')
                    ],
                    [
                        'waktu' => $request->masuk,
                        'terlambat' => Carbon::parse($request->masuk) > $toleransi ? $toleransi->diffInSeconds(Carbon::parse($request->masuk)) : 0,
                        'auto_presensi' => false,
                        'tipe' => $request->tipemasuk,
                        'jadwal_id' => $user->jadwal_id,
                        'foto' => NULL,
                        'ip_location' => \request()->ip(),
                        'lat' => $request->address['lat'] ?? null,
                        'long' => $request->address['long'] ?? null,
                        'address' => $alamatKu ?? null,
                        'catatan' => $request->catatan,
                        'status_presensi_id' => $request->status_presensi_id
                    ]
                );

                $pulang = Presensi::whereTipe(3)->updateOrCreate(
                    [
                        "user_id" => $value,
                        'tanggal' => Carbon::parse($request->masuk)->format('Y-m-d')
                    ],
                    [
                        'waktu' => $request->pulang,
                        'terlambat' => 0,
                        'tipe' => $request->tipepulang,
                        'auto_presensi' => false,
                        'jadwal_id' => $user->jadwal_id,
                        'foto' => NULL,
                        'ip_location' => \request()->ip(),
                        'pulang_cepat' => 0,
                        'catatan' => request()->catatan ?? NULL,
                        'jam_pulang_cepat' => 0,
                        // 'jam_kerja' => 20000,
                        'jam_kerja' => Carbon::parse($masuk->waktu)->diffInSeconds($request->pulang),
                        'lat' => $request->address['lat'] ?? null,
                        'long' => $request->address['long'] ?? null,
                        'address' => $alamatKu ?? null,
                        'status_presensi_id' => $request->status_presensi_id
                    ]
                );
            }

            return redirect()->back()->with(['pesan' => 'Berhasil melakukan absensi manual', 'tipe' => 'success', 'status' => \App\StatusPresensi::find($request->status_presensi_id)->nama]);
        } catch (\Illuminate\Database\QueryException $th) {
            return redirect()->back()->with(['pesan' => 'Gagal melakukan absensi manual', 'tipe' => 'success', 'error' => $th]);
        }


        return redirect()->back()->with(['tipe' => 'sukses']);
    }
}
