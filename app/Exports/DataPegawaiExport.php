<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataPegawaiExport implements FromView
{
    protected $unitkerja;

    function __construct($unitkerja){
        $this->unitkerja = $unitkerja;
    }

    public function view(): View
    {
        if ($this->unitkerja == 'all') {
            $data = User::whereRoleId('pegawai')->orderBy('unitkerja_id')->orderBy('name')->get();
        }else{
             $data = User::whereRoleId('pegawai')->whereUnitkerjaId($this->unitkerja)->orderBy('name')->get();
        }

        return view('Exports.data-pegawai', [
                'data' => $data,
                'unit' => $this->unitkerja
        ]);
    }
}
