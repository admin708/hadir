<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Libur;
use Livewire\WithPagination;
use \Carbon\Carbon;

class Liburs extends Component
{
    use WithPagination;
    public $bulan_tahun, $hari_libur, $liburId, $bulan_key, $liburnya;
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
        // dd($this);
        if ($this->search != null) {
            $data = Libur::whereMonth('bulan_key', $this->search)->whereYear('bulan_key', date('Y'))
            ->paginate();
        }else{
            $data = Libur::whereYear('bulan_key', date('Y'))->orderBy('bulan_key')->paginate();
        }

        // dd($data);

        return view('livewire.Libur.libur', [
            'data' => $data,
            'cari' => $this->search
        ]);
    }

    public function openForm($id)
    {
        $this->createMode = $id;
        $this->updateMode = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->bulan_tahun = null;
        $this->bulan_key = null;
        $this->hari_libur = null;
    }

    public function store()
    {

        $this->validate([
            'bulan_tahun' => 'required|unique:liburs',
            'hari_libur' => 'required'
        ]);

        try {
            Libur::create([
                'bulan_tahun' => Carbon::parse($this->bulan_tahun)->format('Y-m'),
                'bulan_key' => Carbon::parse($this->bulan_tahun)->format('Y-m-d'),
                'hari_libur' => '['.$this->hari_libur.']'
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
        $data = Libur::findOrFail($id);
        $this->emit('edit', $data);
        $this->liburId = $id;
        $this->bulan_tahun = Carbon::parse($data->bulan_tahun)->format('Y-m');
        $this->hari_libur = $data->hari_libur;
        // $this->hari_libur = json_decode($data->hari_libur);
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'bulan_tahun' => 'required',
            'hari_libur' => 'required'
        ]);

        // dd($this->hari_libur);
        // dd(is_array($this->hari_libur));

        if ($this->liburId) {
            $data = Libur::find($this->liburId);
            try {
                $data->update([
                    'bulan_tahun' => $this->bulan_tahun,
                    'bulan_key' => Carbon::parse($this->bulan_tahun)->format('Y-m-d'),
                    'hari_libur' => $this->hari_libur,
                    // 'hari_libur' => '['.$this->hari_libur.']',
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
            $data = Libur::find($id);
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
