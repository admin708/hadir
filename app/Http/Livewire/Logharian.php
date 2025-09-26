<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Livewire\WithPagination;

class Logharian extends Component
{

    use WithPagination;
    public $libur;
    public $hari;
    public $search = '';

    protected $updatesQueryString = [
        ['page' => ['except' => 1]],
        ['search' => ['except' => '']],
    ];

    public function mount()
    {
        $now = now();
        $year = $now->year;
        $month = $now->month;
        $days = $now->daysInMonth;
        $today = $now->day;
        $hari = range(1, $days);
        $getHariLibur = \App\Libur::getHariLibur($month, $year);
        $workDays=collect();
        $weekEnd=collect();
        foreach (range(1, $days) as $day) {
            $date = \Carbon\Carbon::createFromDate($year, $month, $day);
            if ($date->isSunday() === false && $date->isSaturday() === false) {
                $workDays[]=($date->day);
            }else{
                $weekEnd[] = ($date->day);
            }
        }

        $break = $getHariLibur != [] ? $weekEnd->merge(json_decode($getHariLibur))->sort()->unique():$weekEnd;
        // dd($break);
        $this->hari = $hari;
    }


    public function render()
    {

        // dd($this);
        if ($this->search != null) {
        $p_masuk = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $this->search)->whereTipe(1)->first();
        $p_rehat = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $this->search)->whereTipe(2)->first();
        $p_pulang = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', $this->search)->whereTipe(3)->first();

        }else{

        $p_masuk = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))->whereTipe(1)->first();
        $p_rehat = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))->whereTipe(2)->first();
        $p_pulang = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))->whereTipe(3)->first();
        }


        $f_masuk = $p_masuk != null ? asset('storage/foto-kehadiran/'.$p_masuk->foto):'https://via.placeholder.com/70';
        $f_rehat = $p_rehat != null ? asset('storage/foto-kehadiran/'.$p_rehat->foto):'https://via.placeholder.com/70';
        $f_pulang = $p_pulang != null ? asset('storage/foto-kehadiran/'.$p_pulang->foto):'https://via.placeholder.com/70';

        return view('livewire.logharian', [
            'page' => $this->search != null ? $this->search:now()->format('d'),
            'data' => $p_masuk

        ]);
    }
}
