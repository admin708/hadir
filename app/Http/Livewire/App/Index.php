<?php

namespace App\Http\Livewire\App;

use Livewire\Component;
use App\User;
use App\Presensi;
use App\Jadwal;

class Index extends Component
{
    public $page = '';
    public $password, $password_confirmation;
    public $switchJadwal = false; //false jadwal masuk, true jadwal pulang
    public $role, $all_jadwal, $jadwal_absen;
    public $pulang_cepat = false;
    public $p_masuk, $p_pulang, $telat_total, $me, $jumlah_pulang_cepat, $f_masuk, $f_pulang;

    protected $listeners = ['openPulang' => 'switchJadwalPulang'];

    public function switchJadwalPulang($value)
    {
        $this->switchJadwal = $value;
    }

    public function mount()
    {
        $this->role = auth()->user()->role_id;
        $this->all_jadwal = auth()->user()->unitkerja->all_jadwal;
        $this->p_masuk = Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(1)->first();
        $this->p_pulang = Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(3)->first();
        $this->telat_total = Jadwal::getLate(auth()->id())->telat_total;
        $this->jumlah_pulang_cepat = auth()->user()->presensi()->where('user_id', auth()->id())->where('pulang_cepat', 1)->count();

        $this->f_masuk = $this->p_masuk != null ? asset('storage/foto-kehadiran/'.$this->p_masuk->foto):asset('assets/img/sample/photo/1.jpg');
        $this->f_pulang = $this->p_pulang != null ? asset('storage/foto-kehadiran/'.$this->p_pulang->foto):asset('assets/img/sample/photo/1.jpg');

        $me = auth()->user();
        $this->me = $me;
        $this->jadwal_absen = $me->jadwal_id;
    }

    public function render()
    {

        $set = \App\Pengaturan::first();
        $faq = \App\Faq::whereIsShow(true)->get();
        $pulang_cepat = $this->pulang_cepat;

        $this->role = auth()->user()->role_id;
        $this->all_jadwal = auth()->user()->unitkerja->all_jadwal;
        $this->p_masuk = Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(1)->first();
        $this->p_pulang = Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(3)->first();
        $this->telat_total = Jadwal::getLate(auth()->id())->telat_total;
        $this->jumlah_pulang_cepat = auth()->user()->presensi()->where('user_id', auth()->id())->where('pulang_cepat', 1)->count();

        $this->f_masuk = $this->p_masuk != null ? asset('storage/foto-kehadiran/'.$this->p_masuk->foto):asset('assets/img/sample/photo/1.jpg');
        $this->f_pulang = $this->p_pulang != null ? asset('storage/foto-kehadiran/'.$this->p_pulang->foto):asset('assets/img/sample/photo/1.jpg');

        $me = auth()->user();
        $this->me = $me;
        $this->jadwal_absen = $me->jadwal_id;
        $date = now();
        $hadir = Presensi::whereUserId($me->id)->whereMonth('waktu', $date->month)->whereStatusPresensiId(1)
        ->whereYear('waktu', $date->year)->get()
        ->groupBy(function ($val) {
        return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
        });

        $mypres = $me->presensi()->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->latest()->get()
                ->groupBy(function ($val){
                    return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
                });

        $izin  = $me->presensi()->status(3)->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->count();
        $sakit = $me->presensi()->status(4)->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->count();
        $alfa  = $me->presensi()->status(2)->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->count();
        $cuti  = $me->presensi()->status(5)->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->count();
        $dinas  = $me->presensi()->status(6)->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->count();

        return view('livewire.app.index', [
            'set' => $set,
            'faq' => $faq,
            'jadwal' => \App\Jadwal::getJadwalPegawai($me->jadwal_id, $me->id),
            'pulang_cepat' => $this->pulang_cepat,
            'all_jadwal' => $this->all_jadwal,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alfa' => $alfa,
            'mypres' => $mypres,
            'cuti' => $cuti,
            'dinas' => $dinas
        ]);
    }

    public function updatedJadwalAbsen()
    {
        $status = auth()->user();
        $status->update(['jadwal_id' => $this->jadwal_absen]);
        $this->emit('toast', ['isi' => 'Anda beralih ke jadwal '.$status->jadwal->nama]);
    }

    public function updatedPage($page=null)
    {
        $this->page = $page ?? 'presensi';
    }

    public function showPage($page)
    {
        $this->page = $page;
        $this->emit('page', ['page' => $page]);
    }

    public function requestPulangCepat($value, $page)
    {
        $this->pulang_cepat = $value;
        $this->page = $page;
        $this->showPage($this->page);
    }

    public function changePassword()
    {

        // dd($this->password);
        // $this->validate([
        //     'password' => 'required|min:6|confirmed',
        // ]);

        $user= auth()->user()->update(['password' => bcrypt($this->password)]);
        $this->emit('alert', ['pesan'=>'Berhasil diubah']);
    }




}
