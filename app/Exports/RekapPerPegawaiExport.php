<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapPerPegawaiExport implements FromView
{

    protected   $presensi,
                $data,
                $late,
                $jadwal,
                $title,
                $heading,
                $jam_kerja,
                $jam_pulang_cepat;

    public function __construct
        (
            $presensi,
            $data,
            $late,
            $jadwal,
            $title,
            $heading,
            $jam_kerja,
            $jam_pulang_cepat
        )
    {

        $this->presensi = $presensi;
        $this->data = $data;
        $this->late = $late;
        $this->jadwal = $jadwal;
        $this->title = $title;
        $this->heading = $heading;
        $this->jam_kerja = $jam_kerja;
        $this->jam_pulang_cepat = $jam_pulang_cepat;
    }

    public function view(): View
    {
        return view('Exports.presensi', [
            'presensi' => $this->presensi,
            'data' => $this->data,
            'late' => $this->late,
            'jadwal' => $this->jadwal,
            'title' => $this->title,
            'heading' => $this->heading,
            'jam_kerja' => $this->jam_kerja,
            'jam_pulang_cepat' => $this->jam_pulang_cepat,
        ]);
    }
}
