<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hadir extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function buktiMasuk()
    {
        return $this->hasMany('App\Bukti')->whereTipeBukti(1);
    }

    public function buktiKeluar()
    {
        return $this->hasMany('App\Bukti')->whereTipeBukti(2);
    }

    public function buktiAll()
    {
        return $this->hasMany('App\Bukti');
    }
}
