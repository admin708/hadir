<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    protected $guarded = [];

    public function hadir()
    {
        return $this->belongsTo('App\Hadir');
    }
}
