<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Unitkerja;

class Unitkerjas extends Component
{

    public $nama, $unitId, $nip, $pejabat;
    public $jadwal_id = [];
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
        if ($this->search !== null) {
            $data = Unitkerja::withCount(['pegawai' => function($query){
                $query->where('role_id', '<>', 'admin');
            }])->where('nama', 'like', '%'.$this->search.'%')->paginate();
        }

        return view('livewire.Unitkerja.unitkerjas', [
            'data' => $data,
            'cari' => $this->search,
            'jadwal' => \App\Jadwal::get(),
            'user' => \App\User::where('role_id', '<>', 'admin')->get()
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function openForm($id)
    {
        $this->createMode = $id;
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->nama = null;
        $this->nip = null;
        $this->pejabat = null;
        $this->jadwal_id = null;
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|unique:unitkerjas',
            'nip' => 'required',
            'pejabat' => 'required',
            'jadwal_id' => 'required_without_all'
        ]);

        // dd($this->jadwal_id);

        try {
            $update = \App\User::whereUsername($this->nip)->update(['role_id' => 'pimpinan']);
            $save = Unitkerja::create([
                'nama' => $this->nama,
                'nip' => $this->nip,
                'pejabat' => $this->pejabat
            ]);

            $save->all_jadwal()->sync($this->jadwal_id);
            $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'menambah data']);
        } catch (\Illuminate\Database\QueryException $th) {
            $error = $th->errorInfo;
            $this->emit('alert', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
        }

        $this->resetInput();
    }


    public function edit($id)
    {
        $data = Unitkerja::findOrFail($id);
        $this->emit('edit', $data);
        $this->unitId = $id;
        $this->nama = $data->nama;
        $this->nip = $data->nip;
        $this->jadwal_id = $data->all_jadwal !=null ? $data->all_jadwal->pluck('id')->toArray():[];
        $this->pejabat = $data->pejabat;
        $this->updateMode = true;

        // dd($this->jadwal_id);
    }

    public function update()
    {

        // dd($this->jadwal_id);
        if ($this->unitId) {

            // set role pimpinan sebelumnya jadi pegawai jika nip berbeda
            // 1. Jika nip berbeda, maka set role pegawai untuk nip lama dan set role pimpinan untuk nip baru
            // 2. Jika nip sama, maka tidak melakukan apa-apa

            $nip_old = Unitkerja::find($this->unitId)->nip;
            // 1. Jika nip berbeda, maka set role pegawai untuk nip lama dan set role pimpinan untuk nip baru
            if ($this->nip != $nip_old) {
                $set_role_nip_lama = \App\User::whereUsername($nip_old)->update(['role_id' => 'pegawai']);
                // kemudian set role pimpinan untuk nip baru
                $addedPimpinan = Unitkerja::find($this->unitId);
                $addedPimpinan->nip = $this->nip;
                $addedPimpinan->pejabat = $this->pejabat;
                $addedPimpinan->nama = $this->nama;
                $addedPimpinan->save();
                $addedPimpinan->all_jadwal()->sync($this->jadwal_id);
                $set_role_nip_baru = \App\User::whereUsername($this->nip)->update(['role_id' => $this->unitId == 16 ? 'pimpinan_umum': 'pimpinan']);
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'update data']);
            }else {
                $addedPimpinan = Unitkerja::find($this->unitId);
                $addedPimpinan->nip = $this->nip;
                $addedPimpinan->pejabat = $this->pejabat;
                $addedPimpinan->nama = $this->nama;
                $addedPimpinan->save();
                $addedPimpinan->all_jadwal()->sync($this->jadwal_id);
                $set_role_nip_baru = \App\User::whereUsername($this->nip)->update(['role_id' => $this->unitId == 16 ? 'pimpinan_umum': 'pimpinan']);
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'update data']);
            }
            $this->resetInput();
            $this->updateMode = false;
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $data = Unitkerja::find($id);
            $update = \App\User::whereUsername($data->nip)->update(['role_id' => 'pegawai']);
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
