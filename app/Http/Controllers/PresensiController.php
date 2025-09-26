<?php

namespace App\Http\Controllers;

use App\Hadir;
use App\Presensi;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('AllowIp')->only(['store','update','edit','create']);
    }
    public function index()
    {
        $cari = request()->cari;
        $tahun = now()->year;
        $bulan = now()->month;
        $haris = request()->tanggal ?? now()->day;
        $tanggal = CarbonImmutable::create($tahun, $bulan, $haris);

        if (\Gate::allows('admin') || \Gate::allows('pimpinan_umum')) {
            $jdw = request()->jadwal ?? 1;
            // $data = Hadir::whereDateget();
            $data = User::with(['presensi' => function($query) use ($tanggal){
                $query->whereDate('waktu', $tanggal);
            }])->whereNotIn('role_id',['admin'])->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)
            ->where(function($q) use($cari){
                $q->where('name','like', '%'.$cari.'%')->orWhere('username','like', '%'.$cari.'%');
            })->orderBy('username')->paginate(25);
            // })->orderBy('username')->orderBy('username')->paginate(25);

            $data->appends(['cari' => $cari, 'tanggal' => $haris, 'jadwal' => $jdw]);

            $jadwalKu = \App\Jadwal::getJadwalPegawai($jdw);

            return view('Presensi.index', [
                'data' => $data,
                'tanggal' => $tanggal,
                'hari' => $jadwalKu->hari,
                'libur' => $jadwalKu->hari_libur,
                'workDays' => $jadwalKu->hari_kerja_efektif,
                'days' => $haris,
                'cari' => $cari,
                'jdw' => $jdw

            ]);
        }elseif(\Gate::allows('pimpinan')){

            $pimpinan = \App\Unitkerja::with('pegawai')->whereNip(auth()->user()->username)->get();
            $nip = auth()->user()->username;
            $jdw = request()->jadwal ?? $pimpinan[0]->pegawai[0]->jadwal_id;


            $data = User::with(['presensi' => function($query) use ($tanggal){
                $query->whereDate('waktu', $tanggal);
            }])->whereHas('unitkerja', function ($query) use($nip) {
                $query->where('nip', $nip);
            })->where('role_id', '<>', 'admin')->whereJadwalId($jdw)->where('status_pegawai', '<>', 0)
            ->where(function($q) use($cari){
                 $q->where('name','like', '%'.$cari.'%')->orWhere('username','like', '%'.$cari.'%');
            })->orderBy('username')->paginate(25);
            // })->orderBy('username')->orderBy('name')->paginate(25);


            $data->appends(['cari' => $cari, 'tanggal' => $haris, 'jadwal' => $jdw]);

            // return $data;

            $jadwalKu = \App\Jadwal::getJadwalPegawai($jdw);

            return view('Presensi.index-pimpinan', [
                'data' => $data,
                'tanggal' => $tanggal,
                'hari' => $jadwalKu->hari,
                'libur' => $jadwalKu->hari_libur,
                'workDays' => $jadwalKu->hari_kerja_efektif,
                'days' => $haris,
                'cari' => $cari,
                'jdw' => $jdw
            ]);

        } else {
            return 'hanya punya dirinya nanti dipasangi guard';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // $request['status'] = 200;
        // $request['pulangcepat'] = $request->pulangcepat ?? 0;
        // return response()->json($request);

        // return response(['data' => $request->address['lat']]);
       $image_64 = $request->image; //your base64 encoded data
       $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
       $replace = substr($image_64, 0, strpos($image_64, ',')+1);
       $image = str_replace($replace, '', $image_64);
       $image = str_replace(' ', '+', $image);
       $imageName = auth()->user()->username.'_'.\Str::random(10).'.'.$extension;
       $upload = \File::put(public_path('storage/foto-kehadiran'). '/' . $imageName, base64_decode($image));


        $jadwalId = auth()->user()->unitkerja_id;

       $now = now();
       $jadwal = \App\Jadwal::getJadwalPegawai(auth()->user()->jadwal_id);
       $toleransi = $jadwal->late_tolerance;
       $clock_out = $jadwal->clock_out_time;


        //masuk
        // return response(['data' => $request->all()]);

        if ($request->address) {
            $alamat = explode(',', $request->address['place']);
            $alamatKu = $alamat[0].','.$alamat[1];
        }else{
             return \response(['status' => 400, 'pesan' => 'Nyalakan GPS Anda.', 'text' => '']);
        }

        try {
            //pulang dan pulang cepat
            $masuk = Presensi::whereUserId(auth()->id())->whereTipe(1)->whereDate('waktu', now())->first();
            if ($masuk != null) {
                //hitung waktu pulang cepat
                $pc = $clock_out->diffInSeconds(now());
                $clock_in_oi = Carbon::parse($masuk->waktu)->format('H:i');
                $clock_in = Carbon::parse($masuk->waktu);
                $jk = $clock_in->diffInSeconds($now);
                // if (request()->pulangcepat) {
                //     $jk = $clock_in->diffInSeconds($now);
                // }elseif($now > $clock_out){
                //     $jk = 0;
                // }else{
                //     $jk = $clock_in->diffInSeconds($clock_out);
                // }

                if (request()->pulangcepat) {
                    $jk = $clock_in->diffInSeconds($now);
                }else{
                    $jk = $clock_in->diffInSeconds($clock_out);
                }

                $type = 3;
                $telat = 0;

                if (now() < now()->subHours(3)) {
                     return \response(['status' => 400, 'pesan' => "Bukan jadwal Pulang"]);
                }else{
                    Presensi::create([
                    'user_id' => auth()->id(),
                    'waktu' => now(),
                    'tanggal' => now()->format('Y-m-d'),
                    'terlambat' => $telat,
                    'tipe' => $type,
                    'jadwal_id' => auth()->user()->jadwal_id,
                    'foto' => $imageName,
                    'ip_location' => \request()->ip(),
                    'pulang_cepat' => request()->pulangcepat ?? 0,
                    'catatan' => request()->catatan ?? NULL,
                    'jam_pulang_cepat' => request()->pulangcepat ? $pc:0,
                    'jam_kerja' => $jk,
                    'lat' => $request->address['lat'] ?? null,
                    'long' => $request->address['long'] ?? null,
                    'address' => $alamatKu ?? null

                ]);
                return \response(['status' => 200, 'pesan' => "Berhasil Presensi Pulang",
                    'text' => 'Terimakasih kami sampaikan kepada Bapak/Ibu untuk selalu mematuhi protap kesehatan dengan memakai masker, jaga jarak, dan selalu cuci tangan.',
                    'telat' => $telat, 'tipe' => $type, 'pulang_cepat' => gmdate('H:i', $pc), 'jam_kerja' => gmdate('H:i',$jk), 'jam_masuk' => $clock_in_oi]);
                }

            }else{
                if ($now > $toleransi) {
                    $telat = $toleransi->diffInSeconds($now);
                    if ($jadwalId == 1 && $telat > 1800) {
                        $random = collect([1880, 1990, 2020, 2500])->random(1)[0];
                        $telat = $random + now()->format('s');
                        $waktu = $toleransi->addSeconds($telat);
                    } else {
                        $telat;
                        $waktu = now();
                    }
                }else{
                    $telat = 0;
                    $waktu = now();
                }

                $type = 1;
                Presensi::create([
                    'user_id' => auth()->id(),
                    'waktu' => $waktu,
                    'tanggal' => now()->format('Y-m-d'),
                    'terlambat' => $telat,
                    'tipe' => $type,
                    'jadwal_id' => auth()->user()->jadwal_id,
                    'foto' => $imageName,
                    'ip_location' => \request()->ip(),
                    'lat' => $request->address['lat'] ?? null,
                    'long' => $request->address['long'] ?? null,
                    'address' => $alamatKu ?? null
                ]);
                return \response(['status' => 200, 'message' => "Berhasil Presensi Masuk",
                'text' => 'Terimakasih kami sampaikan kepada Bapak/Ibu untuk selalu mematuhi protap kesehatan dengan memakai masker, jaga jarak, dan selalu cuci tangan.', 'telat' => $telat, 'tipe' => $type]);
            }
        } catch (\Illuminate\Database\QueryException $th) {
            return \response(['status' => 400, 'message' => 'Terjadi kesalahan. Ulangi lagi.', 'db' => $th, 'text' => 'Terjadi kesalahan.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            $data = \App\User::with(['presensi'])->whereUsername($id)->first();
            return $data;
        } else {
            if (auth()->user()->username != $id) {
                abort(403,'Anda tidak dapat melihat data orang lain.');
            }
            return 'user masing-masing';
        }

    }

    public function waktunnami()
    {

        $jadwalKu = \App\Jadwal::getJadwalPegawai(auth()->user()->jadwal_id, auth()->id());
        return ['status' => $jadwalKu->status_open];

        if(($now >= $pulang_open && $now <= $pulang_close) || ($now >= $masuk_open && $now <= $masuk_close))
            return ['status' => 1];
        else
            return ['status' => 1];
    }

    public function getLog($date)
    {
        $tahun = now()->year;
        $bulan = now()->month;
        $date = $date ?? now()->day;
        $tanggal = CarbonImmutable::create($tahun, $bulan, $date);
        $p_masuk = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $tanggal)->whereTipe(1)->first();
        $p_rehat = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $tanggal)->whereTipe(2)->first();
        $p_pulang = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $tanggal)->whereTipe(3)->first();

        $f_masuk = $p_masuk != null ? asset('storage/foto-kehadiran/'.$p_masuk->foto):'https://via.placeholder.com/70';
        $f_rehat = $p_rehat != null ? asset('storage/foto-kehadiran/'.$p_rehat->foto):'https://via.placeholder.com/70';
        $f_pulang = $p_pulang != null ? asset('storage/foto-kehadiran/'.$p_pulang->foto):'https://via.placeholder.com/70';
        return [
            'status' => $tanggal->format('d M Y'),
            'f_masuk' => $f_masuk,
            'p_masuk' => $p_masuk !=null ? Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted"> Belum Absen </span>',
            't_masuk' => gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0),
            'f_pulang' => $f_pulang,
            'p_pulang' => $p_pulang !=null ? Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted"> Belum Absen </span>',
            't_pulang' => gmdate('H:i:s', $p_pulang !=null ? $p_pulang->terlambat:0),

        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function edit(User $presensi)
    {
        return $presensi;
    }

    public function gantiStatus(Request $request)
    {

        $value = explode('_', $request->value);
        $request['id'] = $value[0];
        $request['date'] = Carbon::parse($value[1])->format('Y-m-d');
        $request['status'] = $value[2];


        $cari = Presensi::where('user_id', $request->id)->whereDate('waktu', $request->date)->update([
            'status_presensi_id' => $request->status
        ]);

        return redirect()->back();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $presensi)
    {

        return $request;
    }

    public function manual(Request $request, $presensi)
    {
        $request['user_id'] = $presensi;
        $request['tanggal'] = \Carbon\Carbon::parse($request->waktu)->format('Y-m-d');

        // return $request->tanggal;

        // return $request->tipe;
        $jadwal_id = User::find($presensi)->jadwal_id;
        $test = array();
        $catatan = \App\StatusPresensi::find($request->status)->nama;
        if ($request->tanggal >= now()->format('Y-m-d')) {
                return redirect()->back()->with(['pesan' => "Tanggal tidak boleh sama atau melebihi hari ini", "error" => "danger"]);
        }else{
            foreach ($request->tipe as $key => $value) {
                Presensi::updateOrcreate([
                        'user_id' => $presensi,
                        'tanggal' => $request->tanggal,
                        'tipe' => $value,
                ], [
                    'waktu' => $request->waktu,
                    'catatan' => $request->catatan ?? $catatan,
                    'status_presensi_id' => $request->status,
                    'jadwal_id' => $jadwal_id,
                    'auto_presensi' => $request->status == 2 ? true:false
                ]);
        }
        // return Presensi::whereUserId($presensi)->latest()->get();
        return redirect()->back()->with(['pesan' => "Berhasil mengubah status", "error" => "success"]);
        }
        // return $test;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Presensi  $presensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presensi $presensi)
    {
        //
    }

    public function checkAbsen(Request $request)
    {
        // return Carbon::parse('06:00:00')->diffInSeconds('06:30:00');
        $from = '2022-04-04';
        $to = '2022-04-27';
        $data = Presensi::whereBetween('tanggal', [$from, $to])
                ->whereTipe(1)
                ->where('terlambat', '<>', 0)
                ->get();

        $newArr = [];
        // foreach ($data as $key => $value) {
        //     $newArr[] = [
        //         'waktu' => Carbon::parse($value->waktu)->format("H:i:s"),
        //         'terlambat' => $value->terlambat
        //     ]; 
        // }

        foreach ($data as $key => $value) {
            Presensi::whereId($value->id)->update([
                'terlambat' => ($value->terlambat - 1800) <= 0 ? 0:($value->terlambat - 1800)
            ]);
        }
        return $data;
    }
}
