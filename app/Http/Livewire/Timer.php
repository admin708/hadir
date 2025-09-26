<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Timer extends Component
{
    public $clockInShow = false;
    public $clockOutShow = false;

    public function render()
    {
        return view('livewire.timer');
    }

    public function timer()
    {
        $pulang = \App\Jadwal::getJadwalPegawai(auth()->user()->jadwal_id, auth()->id())->clock_out_open;

        if (now() >= $pulang) {
            $this->clockOutShow = true;
            $this->emit('openPulang', $this->clockOutShow);
        }
    }
}
