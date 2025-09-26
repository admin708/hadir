<?php

namespace App\Http\Controllers;

use App\Presensi;
use Illuminate\Http\Request;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function downloadPresensi()
    {
        // return Excel::download(new PresensiExport(2020), 'presensi.xlsx');

        return $this->excel->download(new PresensiExport(2020), 'presensi.xlsx');
    }


}
