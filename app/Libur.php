<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    protected $guarded = [];

    public static function getHariLibur($bulan, $tahun)
    {
        // return $libur = Libur::whereMonth('bulan_tahun', $bulan)->whereYear('bulan_tahun', $tahun)->first();
        $libur = Libur::where('bulan_tahun', 'LIKE', '%'.$bulan)->where('bulan_tahun', 'LIKE', $tahun.'%')->first();
        return $libur != null ? $libur->hari_libur: collect();
    }
}
