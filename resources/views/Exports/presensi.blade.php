<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-5">
        <table border="1" class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle" width="8%">Hari</th>
                    <th class="text-center align-middle" width="10%">Tanggal</th>
                    <th class="text-center align-middle" width="8%">Masuk</th>
                    <th class="text-center align-middle" width="8%">Pulang</th>
                    <th class="text-center align-middle" width="8%">Terlambat</th>
                    <th class="text-center align-middle" width="8%">Cepat Pulang</th>
                    <th class="text-center align-middle" width="8%">Lembur</th>
                    <th class="text-center align-middle" width="8%">Jam Kerja</th>
                    <th class="text-center align-middle" width="29%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                $hari = $jadwal->hari;
                foreach ($jadwal->hari_libur as $key) {
                $hari[$key-1] = 'Libur';
                }
                $fill = [];
                foreach ($presensi as $f => $item){
                $fill[] = \Carbon\Carbon::parse($f)->format('d');
                }
                @endphp

                {{-- @dd($presensi); --}}
                @foreach ($hari as $hr => $val)
                @php
                $presMasuk = "";
                $presPulang = "";
                $hadir = false;
                if (in_array($hr+1, $fill)) {
                $presMasuk=$data->presensi()->where('tipe',1)->whereDate('waktu',\Carbon\CarbonImmutable::create($jadwal->year,
                $jadwal->month, $hr+1))->first();
                $presPulang=$data->presensi()->where('tipe',3)->whereDate('waktu',\Carbon\CarbonImmutable::create($jadwal->year,
                $jadwal->month, $hr+1))->first();
                $hadir = true;
                }else{
                $hadir = false;
                // $presMasuk = "Alfa";$presPulang = "Alfa";
                }
                $date = \Carbon\Carbon::createFromDate($jadwal->year, $jadwal->month, $hr+1);
                @endphp
                @if ($val == 'Libur')
                <tr class="table-warning  text-left text-uppercase">
                    <td class="text-center align-middle">{{  $hr+1 }}</td>
                    <td class="text-center align-middle">{{  $date->dayName }}</td>
                    <td class="text-center align-middle">{{  $date->format('Y-m-d') }}</td>
                    <td colspan="7">Hari Libur</td>
                </tr>
                @else
                <tr class="text-center text-uppercase {{ $hadir ? '':'table-info' }}">
                    <td class="text-center align-middle">{{  $hr+1 }}</td>
                    <td class="align-middle">{{  $date->dayName }}</td>
                    <td class="align-middle">{{  $date->format('Y-m-d') }}</td>
                    @if ($hadir)
                    <td class="align-middle">{!! !empty($presMasuk) ?
                        $presMasuk->auto_presensi ? '<span class="text-danger text-uppercase">Alpa</span>':
                        \Carbon\Carbon::parse($presMasuk->waktu)->format('H:i'):(now()->format('Y-m-d') <= $date->
                            format('Y-m-d') ?
                            '<small style="font-size: 7px" class="text-muted">Belum Check Lock</small>':'<small
                                style="font-size: 7px" class="text-danger">Tidak Check
                                Lock</small>') !!}</td>
                    <td class="align-middle">{!! !empty($presPulang) ?
                    $presPulang->auto_presensi ? '<span class="text-danger text-uppercase">Alpa</span>':
                        \Carbon\Carbon::parse($presPulang['waktu'])->format('H:i'):(now()->format('Y-m-d') <= $date->
                            format('Y-m-d') ?
                            '<small style="font-size: 7px" class="text-muted">Belum Check Lock</small>':'<small
                                style="font-size: 7px" class="text-danger">Tidak Check
                                Lock</small>') !!}</td>
                    <td class="align-middle text-lowercase">
                        {!! $presMasuk['auto_presensi'] ? '<span class="text-danger text-uppercase">Alpa</span>':\Str::of(gmdate('H:i', $presMasuk['terlambat']))->replace(':', 'h').'m' !!}
                    </td>
                    <td class="align-middle text-lowercase">
                        {!! $presPulang['auto_presensi'] ? '<span class="text-danger text-uppercase">Alpa</span>':\Str::of(gmdate('H:i', $presPulang['jam_pulang_cepat']))->replace(':', 'h').'m' !!}
                    </td>
                    <td class="align-middle">-</td>
                    <td class="align-middle text-lowercase">
                        {!! $presPulang['auto_presensi'] ? '<span class="text-danger text-uppercase">Alpa</span>':\Str::of(gmdate('H:i', $presPulang['jam_kerja']))->replace(':', 'h').'m' !!}
                    </td>
                    <td class="align-middle text-left text-lowercase">
                        {!! $presPulang['auto_presensi'] ? '<span class="text-danger text-uppercase">Alpa</span>':'<span class="text-capitalize">'.$presPulang['catatan'].'</span>' !!}
                    </td>
                    @else
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle text-left">
                        {{ now()->format('Y-m-d') <= $date->format('Y-m-d') ? 'Belum Checklock':'Tidak masuk' }}</td>
                    @endif
                </tr>
                @endif
                @endforeach
                @if ($presensi->count() > 0)
                <tr>
                    <td class="align-middle text-right font-weight-bold" colspan="5">T O T A L</td>
                    <td class="text-center align-middle font-weight-bold">
                        {{ $late->telat_totalv2 == 0  ? '00h00m':$late->telat_totalv2}}</td>
                    <td class="text-center align-middle font-weight-bold">
                        {{ $jam_pulang_cepat->jam_pulang_cepatv2 == 0  ? '00h00m':$jam_pulang_cepat->jam_pulang_cepatv2}}
                    </td>
                    <td class="text-center align-middle font-weight-bold"></td>
                    <td class="text-center align-middle font-weight-bold">
                        {{ $jam_kerja->jam_kerjav2 == 0  ? '00h00m':$jam_kerja->jam_kerjav2}}</td>
                    <td class="text-center align-middle font-weight-bold"></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>


</body>

</html>
