<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jadwal;
use \Carbon\Carbon;

class Jadwals extends Component
{
    public $nama, $lama_hari, $weekdays, $clock_in, $clock_out, $toleransi_terlambat, $jadwalId;
    public $updateMode = false;
    public $createMode = false;
    public $search = '';
    public $confirming;

    protected $updatesQueryString = [
        ['page' => ['except' => 1]],
        ['search' => ['except' => '']],
    ];

    public function render()
    {
        if ($this->search != null) {
            // $data = Jadwal::whereName($this->search)->paginate();
            $data = Jadwal::withCount(['pegawai' => function($query){
                $query->where('role_id', 'pegawai');
            }])->where('nama', 'like', '%'.$this->search.'%')->paginate();
        }else{
            $data = Jadwal::withCount(['pegawai' => function($query){
                $query->where('role_id', 'pegawai');
            }])->paginate();
        }

        return view('livewire.Jadwal.jadwals', [
            'data' => $data,
            'cari' => $this->search
        ]);
    }

    public function openForm($id)
    {
        $this->createMode = $id;
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->nama = null;
        $this->lama_hari = null;
        $this->clock_in = null;
        $this->clock_out = null;
        $this->weekdays = null;
        $this->toleransi_terlambat = null;
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|unique:jadwals',
            'lama_hari' => 'required|numeric',
            'clock_in' => 'required',
            'clock_out' => 'required',
            'toleransi_terlambat' => 'required',
        ]);

        try {
            Jadwal::create([
                'nama' => $this->nama,
                'lama_hari' => ($this->lama_hari > 0 && $this->lama_hari <=7 ? $this->lama_hari:5),
                'clock_in' => Carbon::parse($this->clock_in)->translatedFormat('H:i:s'),
                'clock_out' => Carbon::parse($this->clock_out)->translatedFormat('H:i:s'),
                'toleransi_terlambat' => $this->toleransi_terlambat,
            ]);
            $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Tambah data']);
        } catch (\Illuminate\Database\QueryException $th) {
            $error = $th->errorInfo;
            $this->emit('alert', ['type' => 'error', 'title' => 'Tidak dapat menghapus', 'message' => \Str::words($error[2], 4)]);
        }

        $this->resetInput();
    }

    public function edit($id)
    {
        $data = Jadwal::findOrFail($id);
        $this->emit('edit', $data);
        $this->jadwalId = $id;
        $this->nama = $data->nama;
        $this->lama_hari = $data->lama_hari;
        $this->clock_in = Carbon::parse($data->clock_in)->format('H:i:s');
        $this->clock_out = Carbon::parse($data->clock_out)->format('H:i:s');
        $this->toleransi_terlambat = $data->toleransi_terlambat;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'lama_hari' => 'required|numeric',
            'clock_in' => 'required',
            'clock_out' => 'required',
            'toleransi_terlambat' => 'required',
        ]);

        // dd($this->hari_libur);
        // dd(is_array($this->hari_libur));

        if ($this->jadwalId) {
            $data = Jadwal::find($this->jadwalId);
            try {
                $data->update([
                    'nama' => $this->nama,
                    'lama_hari' => ($this->lama_hari > 0 && $this->lama_hari <=7 ? $this->lama_hari:5),
                    'clock_in' => Carbon::parse($this->clock_in)->translatedFormat('H:i:s'),
                    'clock_out' => Carbon::parse($this->clock_out)->translatedFormat('H:i:s'),
                    'toleransi_terlambat' => $this->toleransi_terlambat,
                ]);
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Update data']);
            } catch (\Illuminate\Database\QueryException $th) {
                $error = $th->errorInfo;
                $this->emit('alert', ['type' => 'error', 'title' => 'Tidak dapat menghapus', 'message' => \Str::words($error[2], 4)]);
            }
            $this->resetInput();
            $this->updateMode = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function destroy($id)
    {
        if ($id) {
            $data = Jadwal::find($id);
            try {
                $data->delete();
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data terhapus']);
            } catch (\Illuminate\Database\QueryException $th) {
                $error = $th->errorInfo;
                $this->emit('alert', ['type' => 'error', 'title' => 'Tidak dapat menghapus', 'message' => \Str::words($error[2], 4)]);
            }
        }
    }
}
