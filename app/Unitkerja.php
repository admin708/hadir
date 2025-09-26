<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unitkerja extends Model
{
    protected $guarded = [];

    public function pegawai()
    {
        return $this->hasMany('App\User', 'unitkerja_id');
    }

    public function all_jadwal()
    {
        return $this->belongsToMany('App\Jadwal');
    }
}
