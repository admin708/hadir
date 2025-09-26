<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusPresensi extends Model
{
    protected $guarded = [];

    public function presensis()
    {
        return $this->hasMany('App\Presensi', 'status_presensi_id');
    }
}
