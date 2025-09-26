@php
    $name = $title.'_'.$jadwal->jadwal_name;
    // header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$name.".xls");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo "Some Text"; //no ending ; here
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Rekap' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            background-color: transparent !important;
        }

        @page {
            size: legal landscape;
        }
    </style>
</head>

<body onload="print()">
    <div class="table-responsives">
        <table width="100%" class="my-3">
            <tr>
                <td class="my-2 text-center" width="70px"><a href="{{ url()->previous() }}"><img
                            src="{{ asset('images/uh.png') }}" height="50" alt=""></a> </td>
                <td class="text-center">
                    <h4 class="font-weight-bold">{{ $title }} {{ $jadwal->jadwal_name }}<br>
                        {{ \App\Pengaturan::first()->nama_instansi }}
                    </h4>
                </td>
            </tr>
        </table>
        <table border="1" class="table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="3%">#</th>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="10%">Nama</th>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="5%">Presensi</th>
                    <th class="text-center align-middle" style="font-size:10pt" colspan="{{ count($hari) }}">Bulan
                        {{$bulan[$selected].' '.$tahun }}</th>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="2%">
                        <div style="
                        transform: rotate(90deg)">Total</div>
                    </th>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="3%">
                        <div style="
                            transform: rotate(90deg)">Telat</div>
                    </th>
                    <th class="text-center align-middle" rowspan="2" style="font-size:9pt" width="3%">
                        <div style="
                            transform: rotate(0deg)">%</div>
                    </th>
                </tr>
                <tr>
                    @foreach ($hari as $item)
                    <th style="font-size: 10pt" class="text-center align-middle">{{ $item }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($data ?? [] as $item)
                <tr>
                    <td style="font-size: 9pt" class="text-center align-baseline" rowspan="2">{{ $loop->iteration }}
                    </td>
                    <td style="font-size: 9pt" class="align-baseline" rowspan="2"><span
                            style="font-size: 8pt">`{{ $item->username }}</span><br>{{ $item->name }}</td>
                    <td style="font-size: 9pt" class=" font-weight-bold">Masuk</td>
                    @php

                    foreach ($libur as $key) {
                    $days[$key-1] = 'libur';
                    }

                    $efektif = $hari->count()-$libur->count();

                    $masuk = $item->presensi()->where('user_id', $item->id)->where('tipe', 1)->whereStatusPresensiId(1)->whereMonth('waktu',$selected)->whereYear('waktu', $tahun)->get();

                    $rehat = $item->presensi()->where('user_id', $item->id)->where('tipe', 2)->whereMonth('waktu',
                    $selected)->whereYear('waktu', $tahun)->get();

                    $pulang = $item->presensi()->where('user_id', $item->id)->where('tipe', 3)->whereStatusPresensiId(1)->whereMonth('waktu',$selected)->whereYear('waktu', $tahun)->get();

                    // foreach ($masuk as $key => $value) {
                    // $days[\Carbon\Carbon::parse($value->waktu)->format('d')-1] =
                    // \Carbon\Carbon::parse($value->waktu)->format('H:i');
                    // }

                    $fill = [];
                    foreach ($masuk as $f){
                    $fill[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                    }

                    $fillR = [];
                    foreach ($rehat as $f){
                    $fillR[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                    }

                    $fillP = [];
                    foreach ($pulang as $f){
                    $fillP[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                    }
                    @endphp
                    @foreach ($days as $key => $vmasuk)
                    @php
                    $checked = "";
                    if(in_array($key+1, $fill)){
                    $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe', 1)->whereMonth('waktu',
                    $selected)->whereYear('waktu', $tahun)->whereDate('waktu', \Carbon\CarbonImmutable::create($tahun,
                    $selected, $key+1))->first();
                    $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                    }else{
                    $checked = $item->presensi()->where('user_id', $item->id)->where('tipe',
                    1)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu',
                    \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first()['statusPresensi']['kode'] ?? 'K';
                    }
                    @endphp
                    <td width="2%" style="text-align: center"
                        class="text-center  {{ $vmasuk == 'libur' ? 'table-danger text-danger':'' }} align-middle">{!!
                        $vmasuk == 'libur' ? '<div style="font-size:7pt;transform: rotate(90deg)">Libur</div>':'<small
                            class="font-weight">'.$checked.'</small>' !!}</td>
                    @endforeach
                    <td class="text-center font-weight-bold" style="font-size: 9pt">{{ $masuk->count() }}</td>
                    <td class="text-center font-weight-bold" style="font-size: 8pt">
                        {{ gmdate('H:i',$masuk->sum('terlambat')) }}</td>
                    <td class="text-center font-weight-bold" style="font-size: 9pt">
                        {{ round($masuk->count()/$efektif*100, 2) }}</td>
                </tr>

                {{-- <tr class="">
                    <td class=" font-weight-bold" style="font-size: 10pt">Istirahat</td>
                    @foreach ($days as $key => $vmasuk)
                    @php
                        $checked = "";
                        if(in_array($key+1, $fillR)){
                            $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe', 2)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu', \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first();
                            $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                        }else{
                            $checked = '<span class="text-danger">x</span>';
                        }
                    @endphp
                    <td width="2%" class="text-center  {{ $vmasuk == 'libur' ? 'table-danger text-danger':'' }}
                align-middle">{!! $vmasuk == 'libur' ? '<div style="font-size:9pt;transform: rotate(90deg)">Libur</div>
                ':'<small class="font-weight-bold">'.$checked.'</small>' !!}</td>
                @endforeach
                <td class="text-center font-weight-bold" style="font-size: 10pt">{{ $rehat->count() }}</td>
                <td class="text-center font-weight-bold" style="font-size: 12px">
                    {{ gmdate('H:i',$rehat->sum('terlambat')) }}</td>
                <td class="text-center font-weight-bold" style="font-size: 10pt">
                    {{ round($rehat->count()/$efektif*100, 2) }}</td>
                </tr> --}}
                <tr class="">
                    <td class=" font-weight-bold" style="font-size: 9pt">Pulang</td>
                    @foreach ($days as $key => $vmasuk)
                    @php
                    $checked = "";
                    if(in_array($key+1, $fillP)){
                    $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe', 3)->whereMonth('waktu',
                    $selected)->whereYear('waktu', $tahun)->whereDate('waktu', \Carbon\CarbonImmutable::create($tahun,
                    $selected, $key+1))->first();
                    $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                    }else{
                    $checked = $item->presensi()->where('user_id', $item->id)->where('tipe',
                    3)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu',
                    \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first()['statusPresensi']['kode'] ?? 'K';
                    }
                    @endphp
                    <td width="2%" style="text-align: center"
                        class="text-center {{ $vmasuk == 'libur' ? 'table-danger text-danger':'' }} align-middle">{!!
                        $vmasuk == 'libur' ? '<div style="font-size:7pt;transform: rotate(90deg)">Libur</div>':'<small
                            class="font-weight">'.$checked.'</small>' !!}</td>
                    @endforeach
                    <td class="text-center font-weight-bold" style="font-size: 9pt">{{ $pulang->count() }}</td>
                    <td class="text-center font-weight-bold" style="font-size: 8pt">
                        {{ gmdate('H:i',$pulang->sum('terlambat')) }}</td>
                    <td class="text-center font-weight-bold" style="font-size: 9pt">
                        {{ round($pulang->count()/$efektif*100, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($hari)+7 }}" class="text-center text-muted">No data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
