<?php

namespace App\Http\Controllers;

use App\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Exports\RekapFullExport;
use App\Exports\RekapPerPegawaiExport;
use App\Exports\DataPegawaiExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bulan = ["1" => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $tahun = request()->tahun ?? now()->year;
        $filter = request()->bulan ?? now()->month;
        $cari = request()->cari;
        $jdw = request()->jadwal ?? 1;

        if (\Gate::allows('admin') || \Gate::allows('pimpinan_umum')) {
            $data = \App\User::with(['presensi' => function($query) use($filter, $tahun){
                $query->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->where('role_id', '<>', 'admin')->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)
            ->where(function($q) use($cari){
                $q->where('name','like', '%'.$cari.'%')->orWhere('username','like', '%'.$cari.'%');
            })->orderBy('username')->orderBy('name')->paginate();

            $data->appends(['cari' => $cari, 'bulan' => $filter, 'jadwal' => $jdw]);

            // return $data;

            $jadwalKu = \App\Jadwal::getJadwalPegawai($jdw, '', $filter, $tahun);

            $newHari = $jadwalKu->hari->map(function($x, $y){
                return $x = "";
            });

            // dd($newHari);

            return view('Rekap.index', [
                'bulan' => $bulan,
                'selected' => $filter,
                'libur' => $jadwalKu->hari_libur,
                'hari' => $jadwalKu->hari,
                'data' => $data,
                'tahun' => $tahun,
                'days' => $newHari,
                'cari' => $cari,
                'jdw' => $jdw,
                'jadwalKu' => $jadwalKu
            ]);
        }elseif(\Gate::allows('pimpinan')){
            $pimpinan = \App\Unitkerja::with('pegawai')->whereNip(auth()->user()->username)->get();
            $nip = auth()->user()->username;
            $jdw = request()->jadwal ?? $pimpinan[0]->pegawai[0]->jadwal_id;

             $data = \App\User::with('unitkerja')->with(['presensi' => function($query) use($filter, $tahun){
                $query->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->whereHas('unitkerja', function ($query) use($nip) {
                $query->where('nip', $nip);
            })->where('role_id', '<>', 'admin')->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)
            ->where(function($q) use($cari){
                 $q->where('name','like', '%'.$cari.'%')->orWhere('username','like', '%'.$cari.'%');
            })->orderBy('username')->orderBy('name')->paginate(25);

            $data->appends(['cari' => $cari, 'bulan' => $filter, 'jadwal' => $jdw]);

            // return $data;

            $jadwalKu = \App\Jadwal::getJadwalPegawai($jdw, '' ,$filter, $tahun);

            $newHari = $jadwalKu->hari->map(function($x, $y){
                return $x = "";
            });



            return view('Rekap.index', [
                'bulan' => $bulan,
                'selected' => $filter,
                'libur' => $jadwalKu->hari_libur,
                'hari' => $jadwalKu->hari,
                'data' => $data,
                'tahun' => $tahun,
                'days' => $newHari,
                'cari' => $cari,
                'jdw' => $jdw,
                'jadwalKu' => $jadwalKu
            ]);
        } else {
            // return 'hanya punya dirinya nanti dipasangi guard';
            abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Presensi  $rekap
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // disini lagi nanti semua itu
        // return 'haha';
        $bulan = ["1" => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $tahun = request()->tahun ?? now()->year;
        $filter = request()->bulan ?? now()->month;
        $jdw = request()->jadwal ?? 1;

        if (\Gate::allows('admin') || \Gate::allows('pimpinan') || \Gate::allows('pimpinan_umum')) {
            $data = \App\User::withCount(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun)->whereTipe(1)->whereStatusPresensiId(1);
            }])->with(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->whereUsername($id)->first();
            if (request()->action == 'detail' || request()->action == 'excel') {
                // abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                $presensi = $data->presensi->groupBy(function ($val) {
                    return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
                });

                $late               = \App\Jadwal::getLate($data->id, request()->bulan ?? now()->month, $tahun);
                $jadwal             = \App\Jadwal::getJadwalPegawai($jdw, '', $filter, $tahun);
                $title              = $data->name.' - Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun;
                $heading            = 'Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun;
                $jam_kerja          = \App\Jadwal::getJamKerja($data->id, request()->bulan ?? now()->month, $tahun);
                $jam_pulang_cepat   = \App\Jadwal::getJamPulangCepat($data->id, request()->bulan ?? now()->month, $tahun);

                // dd($late);

                if (request()->action == 'excel') {
                    $name = $data->name.' - Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun;
                    // return Excel::download(new DataPegawaiExport($unitkerja), $title.'.csv');
                    return Excel::download(new RekapPerPegawaiExport(
                        $presensi,
                        $data,
                        $late,
                        $jadwal,
                        $title,
                        $heading,
                        $jam_kerja,
                        $jam_pulang_cepat
                    ), $name.'.csv'); //nanti kulanjut lagi
                }

                return view('Rekap.new_detail', [
                    'presensi' => $presensi,
                    'data' => $data,
                    'late' => $late,
                    'jadwal' => $jadwal,
                    'title' => $title,
                    'heading' => $heading,
                    'jam_kerja' => $jam_kerja,
                    'jam_pulang_cepat' => $jam_pulang_cepat,
                ]);
            } else {
                abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                return view('Rekap.detail', [
                    'data' => $data
                ]);
            }

        } else {
            if (auth()->user()->username != $id) {
                abort(401, 'Anda tidak dapat melihat data orang lain.');
            }
            $data = \App\User::withCount(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun)->whereTipe(1)->whereStatusPresensiId(1);
            }])->with(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->whereUsername($id)->first();
            if (request()->action == 'detail') {
                // abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                $presensi = $data->presensi->groupBy(function ($val) {
                    return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
                });
                return view('Rekap.new_detail', [
                    'presensi' => $presensi,
                    'data' => $data,
                    'late' => \App\Jadwal::getLate($data->id, request()->bulan ?? now()->month, $tahun),
                    'jadwal' => \App\Jadwal::getJadwalPegawai($jdw, '', $filter, $tahun),
                    'title' => $data->name.' - Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'heading' => 'Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'jam_kerja' => \App\Jadwal::getJamKerja($data->id, request()->bulan ?? now()->month, $tahun),
                    'jam_pulang_cepat' => \App\Jadwal::getJamPulangCepat($data->id, request()->bulan ?? now()->month, $tahun),
                ]);
            } else {
                abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                return view('Rekap.detail', [
                    'data' => $data
                ]);
            }
        }
    }

    public function export($id)
    {
        // disini lagi nanti semua itu
        // return 'haha';
        $bulan = ["1" => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $tahun = request()->tahun ?? now()->year;
        $filter = request()->bulan ?? now()->month;
        $jdw = request()->jadwal ?? 1;

        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            $data = \App\User::withCount(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun)->whereTipe(1)->whereStatusPresensiId(1);
            }])->with(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->whereUsername($id)->first();
            if (request()->action == 'export') {
                // abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                $presensi = $data->presensi->groupBy(function ($val) {
                    return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
                });

                return view('Rekap.new_detail', [
                    'presensi' => $presensi,
                    'data' => $data,
                    'late' => \App\Jadwal::getLate($data->id, request()->bulan ?? now()->month, $tahun),
                    'jadwal' => \App\Jadwal::getJadwalPegawai($jdw, '', $filter, $tahun),
                    'title' => $data->name.' - Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'heading' => 'Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'jam_kerja' => \App\Jadwal::getJamKerja($data->id, request()->bulan ?? now()->month, $tahun),
                    'jam_pulang_cepat' => \App\Jadwal::getJamPulangCepat($data->id, request()->bulan ?? now()->month, $tahun),
                ]);
            } else {
                abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                return view('Rekap.detail', [
                    'data' => $data
                ]);
            }

        } else {
            if (auth()->user()->username != $id) {
                abort(401, 'Anda tidak dapat melihat data orang lain.');
            }
            $data = \App\User::withCount(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun)->whereTipe(1)->whereStatusPresensiId(1);
            }])->with(['presensi' => function($q) use($filter, $tahun){
                $q->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
            }])->whereUsername($id)->first();
            if (request()->action == 'detail') {
                // abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                $presensi = $data->presensi->groupBy(function ($val) {
                    return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
                });
                return view('Rekap.new_detail', [
                    'presensi' => $presensi,
                    'data' => $data,
                    'late' => \App\Jadwal::getLate($data->id, request()->bulan ?? now()->month, $tahun),
                    'jadwal' => \App\Jadwal::getJadwalPegawai($jdw, '', $filter, $tahun),
                    'title' => $data->name.' - Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'heading' => 'Laporan Kehadiran Pegawai Bulan '.$bulan[$filter]. ' '.$tahun,
                    'jam_kerja' => \App\Jadwal::getJamKerja($data->id, request()->bulan ?? now()->month, $tahun),
                    'jam_pulang_cepat' => \App\Jadwal::getJamPulangCepat($data->id, request()->bulan ?? now()->month, $tahun),
                ]);
            } else {
                abort(404, 'Laman '.request()->action.' belum tersedia. Dalam pengembangan');
                return view('Rekap.detail', [
                    'data' => $data
                ]);
            }
        }
    }

    public function cetak(Request $request, $tipe)
    {

        // return 'halo';
        $bulan = ["1" => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $tahun = request()->tahun ?? now()->year;
        $filter = request()->bulan ?? now()->month;

        if ($tipe == 'full') {
            if (\Gate::allows('pimpinan')) {
                $pimpinan = \App\Unitkerja::with('pegawai')->whereNip(auth()->user()->username)->get();
                $nip = auth()->user()->username;
                $jdw = request()->jadwal ?? $pimpinan[0]->pegawai[0]->jadwal_id;

                $data = \App\User::with(['presensi' => function($query) use($filter, $tahun){
                    $query->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
                }])->whereHas('unitkerja', function($query) use($nip){
                    $query->where('nip', $nip);
                })->where('role_id','<>','admin')->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)->orderBy('unitkerja_id')->orderBy('name')->get();
            }else{
                $jdw = request()->jadwal ?? 1;
                $data = \App\User::with(['presensi' => function($query) use($filter, $tahun){
                $query->whereMonth('waktu', $filter)->whereYear('waktu', $tahun);
                }])->where('role_id','<>','admin')->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)->orderBy('unitkerja_id')->orderBy('name')->get();
            }

            $jadwalKu = \App\Jadwal::getJadwalPegawai($jdw, null, $filter, $tahun);

            $newHari = $jadwalKu->hari->map(function($x, $y){
                return $x = "";
            });

            $title = 'Rekap Presensi '.$jadwalKu->now->translatedFormat('F Y');

            return view('Cetak.rekap-full', [
                'bulan' => $bulan,
                'selected' => $filter,
                'data' => $data,
                'tahun' => $tahun,
                'days' => $newHari,
                'title' => $title,
                'libur' => $jadwalKu->hari_libur,
                'hari' => $jadwalKu->hari,
                'jdw' => $jdw,
                'jadwal' => $jadwalKu
            ]);

        } elseif($tipe == 'summary' && (\Gate::allows('admin') || \Gate::allows('pimpinan'))) {
            // return 'diizinkan';
            abort(404, 'Dalam pengembangan');
        } else {
            abort(404);
        }

    }
}
