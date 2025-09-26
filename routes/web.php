<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('hitung/post', function(){
    // return request();
    if (!\Gate::denies('pegawai')) {
        abort(403, 'Anda tidak memiliki hak melakukan ini.');
    }
    $clock_in = \Carbon\Carbon::parse(request()->waktu);
    $pecah = \Str::of(\Carbon\Carbon::parse(request()->waktu)->format('Y-m-d'))->explode('-');
    $masuk = \Carbon\Carbon::create($pecah[0], $pecah[1], $pecah[2], 7, 30, 0, 'Asia/Makassar');
    $pulang = !$masuk->isFriday()
                ? \Carbon\Carbon::create($pecah[0], $pecah[1], $pecah[2], 16, 30, 0, 'Asia/Makassar')
                : \Carbon\Carbon::create($pecah[0], $pecah[1], $pecah[2], 16, 00, 0, 'Asia/Makassar');

    $telat = $clock_in > $masuk ? $clock_in->diffInSeconds($masuk):0;
    $jamkerja = $clock_in->diffInSeconds($pulang);

    // return $telat.' edit '.$clock_in.' masuk '.$masuk.' pulang '.$pulang.' jamkerja'.$jamkerja;

    $data = [
        'user_id' => request()->user_id,
        'jam_masuk' => $clock_in,
        'masuk' => $masuk,
        'pulang' => $pulang,
        'telat' => $telat,
        'jamkerja' => $jamkerja,
        'catatan' => request()->catatan
    ];

    // dd ($data);

    // if (today() < request()->waktu) {
    //     return redirect()->back()
    //             ->withInput(request()->input())
    //             ->with(['pesan' => 'Tanggal edit tidak boleh lebih besar dari hari ini.', 'tipe' => 'danger']);
    // }

    // $updateMasuk = \App\Presensi::whereuserId(request()->user_id)->whereDate('waktu', $clock_in)->whereTipe(1)->first();
    try {
        $masukUpdate = \App\Presensi::whereuserId(request()->user_id)->whereDate('waktu', $clock_in);
        $masukUpdate->whereTipe(1)->first();
        $masukUpdate->update([
            'waktu' => $clock_in,
            'terlambat' => $telat
        ]);

        $pulangUpdate = \App\Presensi::whereuserId(request()->user_id)->whereDate('waktu', $clock_in)->whereTipe(3)->first();
        if ($pulangUpdate != null) {
                $pulangUpdate->update([
                'jam_kerja' => $jamkerja,
                'catatan' => request()->catatan
            ]);
        }

        return redirect()->back()->with(['pesan' => 'Jadwal <strong>'.\App\User::find(request()->user_id)->name.'</strong> berhasil diupdate ke '.$clock_in->format('d M Y H:i:s'), 'tipe' => 'success']);
    } catch (\Illuminate\Database\QueryException $th) {
        //throw $th;
        return redirect()->back()->with(['pesan' => 'Jadwal gagal diupdate', 'tipe' => 'danger']);
    }


    // return redirect()->route('hitung');

    // return ['telat' => $telat, 'telat_nama' => gmdate('H:i:s', $telat), 'jamkerja' => $jamkerja, 'jamkerja_nama' => gmdate('H:i:s', $jamkerja)];
    // return gmdate('H:i:s', $jk);
})->name('hitung.post');

Route::get('hitung', function(){
    $ok = true;
    if (!\Gate::denies('pegawai')) {
        abort(401);
    }
    return view('ralat', compact('ok'));
})->name('hitung');

Route::get('splash', function(){
    // dd(request()->cookie('verified'));
    if (request()->cookie('verified')) {
        return redirect()->route('app');
    }
    return view('welcome');
})->name('welcome')->middleware('NewDomain');

Route::get('new-domain', function(){
    return view('newdomain');
})->name('new-domain');

Route::get('loginAs/{nim?}', function ($nim=null) {
    if ($nim !=null) {
        $data = \App\User::firstWhere('username', $nim);
        \Auth::login($data);
    }

        return redirect()->route('home');
    })->name('loginAs');

Route::get('/', function (){
    if (!request()->cookie('verified')) {
        return redirect()->route('welcome');
    }
    if (\Auth::guard()->check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
})->middleware(['Trial', 'NewDomain'])->name('app'); //AllowIp

Route::get('myIp', function () {
    return request()->ip();
});

Route::get('setCookie/{value}', function ($value) {
     Cookie::queue('verified', $value, 4152240000);
     return redirect()->route('welcome');
})->name('skip');

Route::any('cek', function () {
    $user = \App\User::cekPegawaiHadirToday()->whereId(503)->first();
    $getJadwal = \App\Jadwal::getJadwalPegawai($user->jadwal_id)->is_today_libur;
    return $getJadwal;
    return today()->addHours(23)->addMinutes(59)->addSeconds(59);
});

Route::get('export', 'ExportController@downloadPresensi');
Auth::routes(['register' =>false, 'reset' => false, 'confirm' => false]);
Route::group(['middleware' => ['auth', 'Trial', 'NewDomain']], function () { //AllowIp
    Route::match(['get', 'post'],'home', 'HomeController@index')->name('home');
    Route::get('pegawai/ajax', 'PegawaiController@ajax')->name('pegawai.ajax');
    Route::view('waktuSekarang', 'waktuSekarang')->name('waktuSekarang');
    Route::get('pegawai/reset/{pegawai}/password', 'PegawaiController@reset')->name('pegawai.reset');
    Route::post('pegawai/export', "PegawaiController@export")->name('pegawai.export');
    Route::match(['get', 'post'],'pegawai/import', 'PegawaiController@import')->name('pegawai.import');
    Route::get('presensi/json', 'PresensiController@json')->name('presensi.json');
    Route::get('presensi/ganti-status', 'PresensiController@gantiStatus')->name('presensi.ganti');
    Route::post('presensi/manual/{id}', 'PresensiController@manual')->name('presensi.manual');
    Route::get('presensi/getlog/{date}', 'PresensiController@getLog')->name('presensi.getLog');
    Route::get('rekap/cetak/{tipe}', 'RekapController@cetak')->name('rekap.cetak');
    Route::resource('presensi', 'PresensiController');
    // Route::resource('presensi', 'PresensiController', [
    //         'only' => [
    //             'update',
    //             'store'
    //         ]
    //     ])
    //     ->middleware(['AllowIp']);
    Route::resource('hadir', 'HadirController');
    Route::resource('rekap', 'RekapController');
    Route::get('rekap/export/{id}', 'RekapController@export');
    Route::resource('pegawai', 'PegawaiController');
    Route::resource('pengaturan', 'PengaturanController');
    Route::resource('libur', 'LiburController');
    Route::resource('unitkerja', 'UnitkerjaController');
    Route::resource('faq', 'FaqController');
    Route::resource('jadwal', 'JadwalController');
    Route::get('waktunnami', 'PresensiController@waktunnami')->name('waktunnami');

    Route::view('/new', 'App.index');
    Route::post('ganti-password/{id}', function($id){
        if (request()->has('password')) {
            $validate = request()->validate([
                'password' => 'confirmed|min:6'
            ]);
        }

        $user = \App\User::find($id);
        $user->password = bcrypt(request()->password);
        $user->save();

        return response(['status' => 200, 'data' => $id, 'validasi' => $validate]);
    })->name('ganti.password');

    // Route::view('home', 'AppNew.index')->name('newhome');
    Route::get('newhome', function () {
        return redirect()->route('home');
    });

    Route::view('network-unavailable', 'AppNew.out-of-range')->name('oot');

    Route::get('test', function(){
        return $_SERVER['HTTP_HOST'];
        return [
            'asset_url'  => request()->getHost() == 'hadir.rsuregar.id' ? 'https://hadir.rsuregar.id':'/hadirr',
        ];
    });

    Route::get('manual-import', 'ManualImportController@create')->name('form.import');
    Route::post('manual-import-store', 'ManualImportController@store')->name('store.import');

    Route::post('selesai-cuti', function(){
        $user = auth()->user();
        $user->status_pegawai = 1;
        $user->save();
        return redirect()->back();
    })->name('selesai.cuti');

});

Route::get('cekidot',function(){
        $data = \App\User::whereDoesntHave('isOutByDate', function($q){
            $q->whereDate('tanggal', '2021-04-19');
        })->whereIn('role_id', ['pegawai', 'pimpinan', 'pimpinan_umum'])
        ->where('status_pegawai', '<>', 0)->get();

        // return $data;

        foreach ($data as $key => $value) {
            $pulang = \App\Presensi::updateOrCreate([
                'user_id' => $value->id,
                'tanggal' => '2021-04-19',
            ],[
                'waktu' => '2021-04-19 16:07:28',
                'terlambat' => 0,
                'auto_presensi' => 0,
                'tipe' => 3,
                'jadwal_id' => $value->jadwal_id,
                'foto' => NULL,
                'ip_location' => NULL,
                'pulang_cepat' => 0,
                'catatan' => 'Terjadi kesalahan sistem',
                'jam_pulang_cepat' => 0,
                'jam_kerja' => 33390,
                'lat' => null,
                'long' => null,
                'address' => null
            ]);
        }
        return $data;
});

Route::get('checkAbsen', 'PresensiController@checkAbsen');

