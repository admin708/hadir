<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('tipe', 1)->where('status_presensi_id', $value ?? 1)->groupBy('user_id');
    }

    public function statusPresensi()
    {
        return $this->belongsTo('App\StatusPresensi', 'status_presensi_id');
    }

    protected $casts = [
        'waktu' => 'datetime'
    ];


}
