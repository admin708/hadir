<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Pengaturan;

class Pengaturans extends Component
{
    public $nama_instansi, $email, $jam_masuk, $jam_pulang, $range_masuk, $range_pulang, $domain, $keygen, $theId, $jam_istirahat, $range_istirahat, $toleransi_terlambat;
    public $updateMode = false;
    public $createMode = false;
    public $showConfig = true;
    // public $data;

    // public function mount($data)
    // {
    //     $this->data = $data;
    // }
    public function render()
    {

        return view('livewire.Pengaturan.index', [
            'data' => Pengaturan::first(),
        ]);
    }

    private function resetInput()
    {
        $this->nama_instansi = null;
        $this->email = null;
        $this->jam_masuk = null;
        $this->jam_istirahat = null;
        $this->jam_pulang = null;
        $this->range_masuk = null;
        $this->range_pulang = null;
        $this->range_istirahat = null;
        $this->toleransi_terlambat = null;
        $this->domain = null;
        $this->keygen = null;
    }

    public function openForm($id)
    {
        $this->createMode = $id;
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate([
            'nama_instansi' => 'required',
            'domain' => 'required',
            'keygen' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'jam_istirahat' => 'required',
            // 'range_masuk' => 'required',
            // 'range_pulang' => 'required',
            // 'range_istirahat' => 'required'
        ]);

        try {
            Pengaturan::create([
                'nama_instansi' => $this->nama_instansi,
                'email' => $this->email,
                'domain' => request()->getHost(),
                'keygen' => $this->keygen,
                'jam_masuk' => $this->jam_masuk,
                'jam_pulang' => $this->jam_pulang,
                'jam_istirahat' => $this->jam_istirahat,
                'range_masuk' => '['.$this->range_masuk.']',
                'range_pulang' => '['.$this->range_pulang.']',
                'range_istirahat' => '['.$this->range_istirahat.']',
                'toleransi_terlambat' => $this->toleransi_terlambat
            ]);
            $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'membuat data']);
        } catch (\Illuminate\Database\QueryException $th) {
            $error = $th->errorInfo;
            $this->emit('alert', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
        }

        // $this->resetInput();
    }

    public function edit($id)
    {
        $data = Pengaturan::findOrFail($id);
        $this->emit('edit', $data);
        $this->theId = $id;
        $this->nama_instansi = $data->nama_instansi;
        $this->email = $data->email;
        $this->jam_masuk = $data->jam_masuk;
        $this->jam_istirahat = $data->jam_istirahat;
        $this->jam_pulang = $data->jam_pulang;
        $this->range_masuk = $data->range_masuk;
        // $this->range_masuk = json_decode($data->range_masuk);
        // $this->range_istirahat = json_decode($data->range_istirahat);
        // $this->range_pulang = json_decode($data->range_pulang);
        $this->range_istirahat = $data->range_istirahat;
        $this->range_pulang = $data->range_pulang;
        $this->domain = $data->domain;
        $this->keygen = $data->keygen;
        $this->toleransi_terlambat = $data->toleransi_terlambat;
        $this->updateMode = true;
        $this->showConfig = false;
    }

    public function update()
    {
        $this->validate([
            'nama_instansi' => 'required',
            'domain' => 'required',
            'keygen' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'range_masuk' => 'required',
            'range_pulang' => 'required',
            'jam_istirahat' => 'required',
            'range_istirahat' => 'required'
        ]);
            // dd(is_array($this->range_masuk));
        if ($this->theId) {
            $data = Pengaturan::find($this->theId);
            try {
                $data->update([
                    'nama_instansi' => $this->nama_instansi,
                    'email' => $this->email,
                    'domain' => $this->domain,
                    'keygen' => $this->keygen,
                    'jam_masuk' => $this->jam_masuk,
                    'jam_istirahat' => $this->jam_istirahat,
                    'jam_pulang' => $this->jam_pulang,
                    'range_masuk' => $this->range_masuk,
                    'range_istirahat' => $this->range_istirahat,
                    'range_pulang' => $this->range_pulang,
                    'toleransi_terlambat' => $this->toleransi_terlambat
                ]);
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'update data']);
            } catch (\Illuminate\Database\QueryException $th) {
                $error = $th->errorInfo;
                $this->emit('alert', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
            }
            $this->resetInput();
            $this->updateMode = false;
            $this->showConfig = true;
        }
    }
}
