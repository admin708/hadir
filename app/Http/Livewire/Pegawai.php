<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Imports\PegawaiImport;
use App\Exports\DataPegawaiExport;
use Maatwebsite\Excel\Facades\Excel;

class Pegawai extends Component
{
    use WithPagination;

    public $unitkerja_id, $name, $email, $username, $role_id, $userId, $file, $jadwal_id;
    public $updateMode = false;
    public $createMode = false;
    public $search = '';
    public $confirming;
    public $confirmReset;

    protected $updatesQueryString = [
        ['page' => ['except' => 1]],
        ['search' => ['except' => '']],
    ];

    public function render()
    {
        $cari = $this->search;
        if ($this->search != null && \Str::length($cari) >= 4) {
            $data = User::where('role_id', '<>', 'admin')->where(function ($q) use($cari){
            $q->where('name', 'like', '%'.$cari.'%')->orWhere('email', 'like', '%'.$cari.'%');
            $q->orWhere('username', 'like', '%'.$cari.'%');
            })->orderByDesc('unitkerja_id')->orderBy('name')->paginate();

            $data->appends(['page', 'search']);
        }else{
            $data = User::where('role_id', '<>', 'admin')->where('status_pegawai', '<>', 0)->orderByDesc('unitkerja_id')->orderBy('name')->paginate();
            $data->appends(['page', 'search']);
        }

        // dd($data);

        $unit = \App\Unitkerja::get();
        $jadwal = \App\Jadwal::get();

        return view('livewire.Pegawai.pegawai', [
            'data' => $data,
            'cari' => $this->search,
            'unit' => $unit,
            'jadwal' => $jadwal
        ]);
    }

    public function openForm($id)
    {
        $this->createMode = $id;
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->email = null;
        $this->username = null;
        $this->role_id = null;
        $this->jadwal_id = null;
        $this->unitkerja_id = null;
    }

    public function store()
    {
        $this->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'jadwal_id' => 'required',
            'unitkerja_id' => 'required',
            // 'email' => 'exists:users'
        ]);

            // dd($this);

        try {
            User::firstOrCreate([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'unitkerja_id' => $this->unitkerja_id,
                'jadwal_id' => $this->jadwal_id,
                'role_id' => $this->role_id ?? 'pegawai',
                'password' => \bcrypt($this->username)
            ]);
            $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'menambah data']);
        } catch (\Illuminate\Database\QueryException $th) {
            $error = $th->errorInfo;
            $this->emit('alert', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
        }

        $this->resetInput();
    }


    public function edit($id)
    {
        $data = User::findOrFail($id);
        $this->emit('edit', $data);
        $this->userId = $id;
        $this->username = $data->username;
        $this->name = $data->name;
        $this->jadwal_id = $data->jadwal_id;
        $this->unitkerja_id = $data->unitkerja_id;
        $this->email = $data->email;
        $this->updateMode = true;
    }

    // public function download()
    // {
    //     $id = $this->unitkerja_id;
    //     $unitkerja = \App\Unitkerja::find($id);
    //     $title = 'Data Pegawai '. ($unitkerja != null ? 'Semua Unit Kerja':'Unit Kerja '.$unitkerja->nama);
    //     return Excel::download(new DataPegawaiExport($id), $title.'.xlsx');
    // }

    public function resetPassword($id)
    {
        $data = User::findOrfail($id);
        try{
            $random = mt_rand(100000, 999999);
            $data->update(['password' => \bcrypt($random)]);
            $this->emit('reset', ['type' => 'success', 'title' => $random, 'message' => 'Berhasil Reset Password Baru pengguna. Silakan di copy+paste sebelum ditutup.']);
        } catch (\Illuminate\Database\QueryException $th){
            $error = $th->errorInfo;
            $this->emit('reset', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
        }

        $this->confirmReset = false;
    }

    public function update()
    {
        // $this->validate([
        //     'name' => 'required|min:5',
        //     'email' => 'required|email:rfc,dns'
        // ]);

        // dd($this);

        if ($this->userId) {
            $data = User::find($this->userId);
            try {
                $data->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'jadwal_id' => $this->jadwal_id,
                    'unitkerja_id' => $this->unitkerja_id
                ]);
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'update data']);
            } catch (\Illuminate\Database\QueryException $th) {
                $error = $th->errorInfo;
                $this->emit('alert', ['type' => 'error', 'title' => 'Ooups...', 'message' => \Str::words($error[2], 4)]);
            }
            $this->resetInput();
            $this->updateMode = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function confirmReset($id)
    {
        $this->confirmReset = $id;
    }

    public function destroy($id)
    {
        if ($id) {
            $data = User::find($id);
            try {
                $data->delete();
                $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data terhapus']);
            } catch (\Illuminate\Database\QueryException $th) {
                $error = $th->errorInfo;
                $this->emit('alert', ['type' => 'error', 'title' => 'Tidak dapat menghapus', 'message' => \Str::words($error[2], 4)]);
            }
        }
    }

    public function cuti($id)
    {
        $update= User::find($id)->update(['status_pegawai' => 2]);
        $this->emit('alert', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Status Pegawai Cuti']);
    }
}
