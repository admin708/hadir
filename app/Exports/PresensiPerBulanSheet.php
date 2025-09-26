<?php

namespace App\Exports;

use App\User;
use App\Presensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromQuery;
use DateTime;
use Carbon\Carbon;

class PresensiPerBulanSheet implements FromView, WithTitle
{
    private $month;
    private $year;

    public function __construct(int $year, int $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    // public function query()
    // {
    //     return
    //     Presensi::query()
    //             ->whereYear('waktu', $this->year)
    //             ->whereMonth('waktu', $this->month);
    // }

    public function view(): View
    {

        $data =  Presensi::query()
                ->whereYear('waktu', $this->year)
                ->whereMonth('waktu', $this->month);

        return view('Exports.presensi', [
                'data' => $data,
                'bulan' => $this->month
        ]);
    }

    public function title(): string
    {
        return Carbon::createFromDate($this->year, $this->month)->translatedformat('F Y');
    }

}
