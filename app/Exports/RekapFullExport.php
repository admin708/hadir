<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapFullExport implements FromView
{
    protected $bulan;
    protected $filter;
    protected $libur;
    protected $hari;
    protected $data;
    protected $tahun;
    protected $title;
    protected $newHari;

    function __construct($bulan=null, $filter=null, $libur=null, $hari=null, $data=null, $tahun=null, $title=null, $newHari=null){
        $this->bulan = $bulan;
        $this->filter = $filter;
        $this->libur = $libur;
        $this->hari = $hari;
        $this->data = $data;
        $this->tahun = $tahun;
        $this->title = $title;
        $this->newHari = $newHari;
    }

    public function view(): View
    {
        return view('Exports.rekap-full', [
                'bulan' => $this->bulan,
                'selected' => $this->filter,
                'libur' => $this->libur,
                'hari' => $this->hari,
                'data' => $this->data,
                'tahun' => $this->tahun,
                'days' => $this->newHari,
                'title' => $this->title
        ]);
    }
}
