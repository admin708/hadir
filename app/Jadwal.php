<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Pengaturan;
use App\User;

class Jadwal extends Model
{
    protected $guarded = [];

    public function getClockInAttribute($value)
    {
        return Carbon::parse($value)->translatedFormat('H:i:s');
    }

    public function getClockOutAttribute($value)
    {
        return Carbon::parse($value)->translatedFormat('H:i:s');
    }

    public function pegawai()
    {
        return $this->hasMany('App\User', 'jadwal_id');
    }

    public function unitkerja()
    {
        return $this->belongsToMany('App\Unitkerja');
    }



    public static function getJadwalPegawai($jadwal_id, $userId=null, $bulan='', $tahun='')
    {

        // return $jadwal_id;
        $user = $userId == null ? auth()->user():User::find($userId);
        $bln = $bulan == null ? now()->month:$bulan;
        $thn = $tahun == null ? now()->year:$tahun;
        $set = Pengaturan::first();
        $jadwal = Jadwal::withCount('pegawai')->find($jadwal_id);
        // dd($jadwal);
        $now = CarbonImmutable::create($thn, $bln, today()->day);
        $range_in = json_decode($set->range_masuk);
        $range_out = json_decode($set->range_pulang);
        if ($jadwal == null) {
            return $jadwal;
        }

        $in = Carbon::parse($jadwal->clock_in);
        $out = $jadwal->is_night ? Carbon::parse($jadwal->clock_out)->addDays(1):Carbon::parse($jadwal->clock_out);
        $year = $now->year;
        $month = $now->month;
        $days = $now->daysInMonth;
        $today = now()->day;
        $hari = collect(range(1, $days));
        $getHariLibur = \App\Libur::getHariLibur($month, $year);
        $workDays=collect();
        $weekEnd=collect();

        // get weekdays
        foreach ($hari as $day) {
            $date = Carbon::createFromDate($year, $month, $day);
            switch ($jadwal->lama_hari) {
                case 5:
                    if ($date->isSunday() === false && $date->isSaturday() === false) {
                        $workDays[]=($date->day);
                    }else{
                        $weekEnd[] = ($date->day);
                    }
                    break;
                case 6:
                    if ($date->isSunday() === false) {
                        $workDays[]=($date->day);
                    }else{
                        $weekEnd[] = ($date->day);
                    }
                    break;
                default:
                    $workDays[] = $date->day;
                    break;
            }

        }

        //merge weekdays and hari libur
        switch ($jadwal->lama_hari) {
            case 5:
            case 6:
                $libur = collect($getHariLibur != [] ? $weekEnd->merge(json_decode($getHariLibur))->sort()->unique():$weekEnd);
                break;

            default:
                $libur = collect();
                break;
        }

        $realWorkDays = $hari->count()-$libur->count();
        $isTodayLibur = $libur->contains($today);
        $isInToday = $user->isInToday()->count();
        $isOutToday = $user->isOutToday()->count();

        // add automatic 30 menit if today is friday
        if (now()->isFriday()){
            $in_open = Carbon::parse($in)->addMinutes(0)->addHours($range_in[0] > 0 ? - $range_in[0]:0);
            $in_close = Carbon::parse($in)->addMinutes(0)->addHours($range_in[1]);
            $out_open = Carbon::parse($out)->addMinutes(30)->addHours($range_out[0] > 0 ? - $range_out[0]:0);
            $out_close = Carbon::parse($out)->addMinutes(0)->addHours($range_out[1]);
        }else
        {
            $in_open = Carbon::parse($in)->addHours($range_in[0] > 0 ? - $range_in[0]:0);
            $in_close = Carbon::parse($in)->addHours($range_in[1]);
            $out_open = Carbon::parse($out)->addHours($range_out[0] > 0 ? - $range_out[0]:0);
            $out_close = Carbon::parse($out)->addHours($range_out[1]);
        }

        $status = ($now >= $out_open && $now <= $out_close) || ($now >= $in_open && $now <= $in_close) ? 1:0;

        // if ($jadwal == null) {
        //     return null;
        // }

        // return $today;

        return (object)[
            'jadwal_id' => $jadwal->id,
            'jadwal_name' => $jadwal->nama,
            'jumlah_pegawai' => $jadwal->pegawai_count,
            'hari_kerja_efektif' => $realWorkDays,
            'hari_libur' => $libur,
            'hari_libur_selain_weekend' => json_decode($getHariLibur),
            'jumlah_hari_libur' => $libur->count(),
            'now' => $now,
            'days_in_month' => $days,
            'month' => $month,
            'month_name' =>  Carbon::now()->monthName,
            'year' => $year,
            'today' => $today,
            'today_name' => Carbon::now()->dayName == 'Minggu' ? 'Ahad':Carbon::now()->dayName,
            'is_today_libur' => $isTodayLibur,
            'is_friday' => now()->isFriday(),
            'is_in_today' => $isInToday,
            'is_out_today' => $isOutToday,
            'status_open' => $status,
            'clock_in_open' => $in_open,
            'clock_in_time' => $in,
            'clock_in_close' => $in_close,
            'clock_out_open' => $out_open,
            'clock_out_time' => $out,
            'clock_out_close' => $out_close,
            'late_tolerance' => Carbon::parse($in)->addMinutes($jadwal->toleransi_terlambat),
            'hari' => $hari,
        ];
    }

    public static function getLate($userId, $month=null, $tahun=null)
    {
        $bulan = $month ?? now()->month;
        $me = \App\User::find($userId);
        $telat_in = $me->presensi()->where('auto_presensi',false)->where('user_id', $userId)->whereYear('waktu', $tahun ?? now()->year)->whereMonth('waktu', $bulan)->where('tipe', 1)->sum('terlambat');

        // dd($telat_in);
        $telat_out = $me->presensi()->where('user_id', $userId)->whereYear('waktu', $tahun ?? now()->year)->whereMonth('waktu', $bulan)->where('tipe', 3)->sum('terlambat');
        $telat_total = $me->presensi()->where('user_id', $userId)->whereYear('waktu', $tahun ?? now()->year)->whereMonth('waktu', $bulan)->sum('terlambat');

        // return $telat_in;
        $total = $telat_in;
        $jam = floor($total/3600);
        $sisajam = ($total%3600);
        $menit = floor($sisajam/60);
        $sisamenit = ($sisajam%60);
        $ha = '';
        $hav2 = '';
        if($jam > 0) { $hav2 .= $jam."h"; $ha .= $jam." jam ";}
        if($menit > 0) { $hav2 .= $menit."m"; $ha .= $menit." menit ";}
        if($sisamenit>0) {
            // $hav2 .= $sisamenit;
             $ha .= $sisamenit. " detik";
        }

        return (object)[
            'telat_in' => $telat_in,
            'telat_out' => $telat_out,
            'telat_total' => $ha !='' ? $ha:0,
            'telat_totalv2' => $hav2 !='' ? $hav2:0
        ];
    }

    public static function getJamKerja($userId, $month=null, $tahun=null)
    {
        $bulan = $month ?? now()->month;
        $me = \App\User::find($userId);
        $jam_kerja = $me->presensi()->where('user_id', $userId)->whereYear('waktu', $tahun ?? now()->year)->whereMonth('waktu', $bulan)->sum('jam_kerja');

        $total = $jam_kerja;
        $jam = floor($total/3600);
        $sisajam = ($total%3600);
        $menit = floor($sisajam/60);
        $sisamenit = ($sisajam%60);
        $ha = '';
        $hav2 = '';
        if($jam > 0) { $hav2 .= $jam."h"; $ha .= $jam." jam ";}
        if($menit > 0) { $hav2 .= $menit."m"; $ha .= $menit." menit ";}
        if($sisamenit>0) {
            // $hav2 .= $sisamenit;
             $ha .= $sisamenit. " detik";
        }

        // return 'wkwkw';

        return (object)[
            'jam_kerja' => $ha !='' ? $ha:0,
            'jam_kerjav2' => $hav2 !='' ? $hav2:0
        ];
    }

    public static function getJamPulangCepat($userId, $month=null, $tahun=null)
    {
        $bulan = $month ?? now()->month;
        $me = \App\User::find($userId);
        $data = $me->presensi()->where('user_id', $userId)->whereYear('waktu', $tahun ?? now()->year)->whereMonth('waktu', $bulan)->sum('jam_pulang_cepat');

        $total = $data;
        $jam = floor($total/3600);
        $sisajam = ($total%3600);
        $menit = floor($sisajam/60);
        $sisamenit = ($sisajam%60);
        $ha = '';
        $hav2 = '';
        if($jam > 0) { $hav2 .= $jam."h"; $ha .= $jam." jam ";}
        if($menit > 0) { $hav2 .= $menit."m"; $ha .= $menit." menit ";}
        if($sisamenit>0) {
            // $hav2 .= $sisamenit;
             $ha .= $sisamenit. " detik";
        }

        return (object)[
            'jam_pulang_cepat' => $ha !='' ? $ha:0,
            'jam_pulang_cepatv2' => $hav2 !='' ? $hav2:0
        ];
    }


}
