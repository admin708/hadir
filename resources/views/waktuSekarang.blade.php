@php
    $set = \App\Pengaturan::find(1);
    $now = \Carbon\Carbon::now();
    $timeIn = \Carbon\Carbon::parse($set->jam_masuk)->addDays(1);
    $timeOut = \Carbon\Carbon::parse($set->jam_pulang)->addDays(1);
    $masuk = ($now->isFriday()) ? $timeIn->addMinutes(30) : $timeIn;
    $pulang = ($now->isFriday()) ? $timeOut->addMinutes(30) : $timeOut;

    $remain_out = $now->diff($pulang)->format('%H jam %I menit %S detik');
    $remain_in = $now->diff($masuk)->format('%H jam %I menit %S detik');

    // dd($startTime->isSaturday());
@endphp

{{ $now->translatedFormat('d F Y H:i:s a') }}<br/>
Jam Masuk : {{ $masuk->translatedFormat('H:i:s a') }}<br/>
Jam Pulang : {{ $pulang->translatedFormat('H:i:s a') }}<br/>
Absen Pulang akan terbuka lagi {{ $remain_out }} <br/>
Absen Masuk akan terbuka lagi {{ $remain_in }} <br/>




