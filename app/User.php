<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'unitkerja_id', 'role_id', 'jadwal_id', 'status_pegawai'
    ];

    public function presensi()
    {
        return $this->hasMany('App\Presensi');
    }


    public function isHadirToday()
    {
        return $this->hasMany('App\Presensi')->whereDate('waktu', Carbon::today());
    }

    public static function cekPegawaiHadirToday()
    {
        $data = self::whereDoesntHave('isHadirToday')->whereDoesntHave('jadwal', function($q){
            $q->where('is_night', 1);
        })->with('unitkerja', 'jadwal')->whereIn('role_id', ['pegawai', 'pimpinan', 'pimpinan_umum'])->where('status_pegawai', '<>', 0);
        return $data;
    }

    public function isInToday()
    {
        return $this->hasMany('App\Presensi')->whereDate('waktu', Carbon::today())->whereTipe(1);
    }

    public function isBreakToday()
    {
        return $this->hasMany('App\Presensi')->whereDate('waktu', Carbon::today())->whereTipe(2);
    }

    public function isOutToday()
    {
        return $this->hasMany('App\Presensi')->whereDate('waktu', Carbon::today())->whereTipe(3);
    }

    public function isOutByDate()
    {
        return $this->hasMany('App\Presensi')->whereTipe(3);
    }

    public function kehadiranku()
    {
        return $this->hasMany('App\Hadir')->whereUserId(auth()->id());
    }

    public function myPresensi()
    {
        return $this->hasMany('App\Presensi')->whereUserId(auth()->id());
    }

    public function unitkerja()
    {
        return $this->belongsTo('App\Unitkerja');
    }

    public function is_pimpinan()
    {
        return $this->hasMany('App\Unitkerja', 'nip', 'username');
    }

    public function jadwal()
    {
        return $this->belongsTo('App\Jadwal');
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
