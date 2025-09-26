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

<body onload="print()">
    {{-- {{ $hari }} --}}
    <div class="mt-5 container-fluid">
        <h4 class="pb-2 text-center text-uppercase border-bottom">{{ $heading ?? '' }}</h4>
        <div class="mt-3 mb-3">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="15%">Presensi ID <span class="float-right">:</span></td>
                    <td width="40%">{{ $data->username }}</td>
                    <td width="17%">Jenis Presensi <span class="float-right">:</span></td>
                    <td width="30%">{{ $data->jadwal->nama }}</td>
                </tr>
                <tr>
                    <td>Nama Pegawai <span class="float-right">:</span></td>
                    <td>{{ $data->name }}</td>
                    <td>Jam Masuk &mdash; Pulang <span class="float-right">:</span></td>
                    <td>{!! $data->jadwal->clock_in.' &mdash; '.$data->jadwal->clock_out !!}</td>
                </tr>
                <tr>
                    <td>Total Kehadiran<span class="float-right">:</span></td>
                    <td>{{ $data->presensi_count }} dari {{ $jadwal->hari_kerja_efektif }} hari kerja efektif</td>
                    <td>Nama Departemen <span class="float-right">:</span></td>
                    <td>{{ $data->unitkerja->nama }}</td>
                </tr>
            </table>
        </div>



        <table class="table table-sm table-bordered">
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
                <tr class="text-left table-danger text-danger text-uppercase">
                    <td class="text-center align-middle">{{  $hr+1 }}</td>
                    <td class="text-center align-middle">{{  \Str::of($date->dayName)->replace('Minggu', 'Ahad') }}</td>
                    <td class="text-center align-middle">{{  $date->format('d-m-Y') }}</td>
                    <td colspan="7">Hari Libur</td>
                </tr>
                @else
                <tr class="text-center text-uppercase {{ $hadir ? '':'table-info' }}">
                    <td class="text-center align-middle">{{  $hr+1 }}</td>
                    <td class="align-middle">{{  $date->dayName }}</td>
                    <td class="align-middle">{{  $date->format('d-m-Y') }}</td>
                    @if ($hadir)
                    @if ($presMasuk->status_presensi_id != 1)
                    @php
                        $status = $presMasuk->status_presensi_id;
                    @endphp
                        <td colspan="6" class="text-uppercase bg-{{ $status == 2 ? 'danger':($status == 3 ? 'info':($status == 4 ? 'warning':'success')) }}">{{ $status == 2 ? 'ALFA':($status == 3 ? 'IZIN':($status == 4 ? 'SAKIT':($status == 5 ? 'CUTI':'DINAS'))) }}</td>
                    @else
                    <td class="align-middle">{!! !empty($presMasuk) ?
                        $presMasuk->auto_presensi ? '<span class="text-danger text-uppercase">Alpa</span>':
                        '<img style="height: 50px; border-radius:10px" src="'.asset('storage/foto-kehadiran/'.($presMasuk->foto ?? 'no.jpg')).'"><br>'.
                        \Carbon\Carbon::parse($presMasuk->waktu)->format('H:i'):(now()->format('Y-m-d') <= $date->
                            format('Y-m-d') ?
                            '<small style="font-size: 7px" class="text-muted">Belum Check Lock</small>':'<small
                                style="font-size: 7px" class="text-danger">Tidak Check
                                Lock</small>') !!}</td>
                    <td class="align-middle">{!! !empty($presPulang) ?
                    $presPulang->auto_presensi ? '<span class="text-danger text-uppercase">Alpa</span>':
                    '<img style="height: 50px; border-radius:10px" src="'.asset('storage/foto-kehadiran/'.($presPulang->foto ?? 'no.jpg')).'"><br>'.
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
                    @endif

                    <td class="text-left align-middle text-lowercase">
                        {!! $presPulang['status_presensi_id'] == 2 ? '<span class="text-danger text-uppercase">Alpa</span>':'<span class="text-capitalize">'.$presPulang['catatan'].'</span>' !!}
                    </td>
                    @else
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="align-middle">-</td>
                    <td class="text-left align-middle">
                        {{ now()->format('Y-m-d') <= $date->format('Y-m-d') ? 'Belum Checklock':'Tidak masuk' }}</td>
                    @endif
                </tr>
                @endif
                @endforeach
                @if ($presensi->count() > 0)
                <tr>
                    <td class="text-right align-middle font-weight-bold" colspan="5">T O T A L</td>
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

        {{--
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
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
                @forelse ($presensi as $key => $item)
                @php
                $masuk = $item->where('tipe',1)->first();
                $pulang = $item->where('tipe',3)->first();
                $m = \Carbon\Carbon::parse($masuk['waktu']);
                $p = \Carbon\Carbon::parse($jadwal->clock_out_time);
                $cp = \Carbon\Carbon::parse($pulang['waktu']);
                @endphp
                <tr>
                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($key)->translatedFormat('l') }}</td>
        <td class="text-center align-middle">{{ \Carbon\Carbon::parse($key)->translatedFormat('d-m-Y') }}
        </td>
        <td class="text-center align-middle">{!! !empty($masuk) ?
            \Carbon\Carbon::parse($masuk->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<small
                style="font-size: 9px" class="text-muted">Belum Check Lock</small>':'<small style="font-size: 9px"
                class="text-danger">Tidak Check
                Lock</small>') !!}</td>
        <td class="text-center align-middle">{!! !empty($pulang) ?
            \Carbon\Carbon::parse($pulang->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<small
                style="font-size: 9px" class="text-muted">Belum Check Lock</small>':'<small style="font-size: 9px"
                class="text-danger">Tidak Check
                Lock</small>') !!}</td>
        <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('terlambat')) }}</td>
        <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('jam_pulang_cepat')) }}</td>
        <td class="text-center align-middle">-</td>
        <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('jam_kerja')) }}</td>
        <td class="align-middle">{{ $pulang['pulang_cepat'] ? $pulang['catatan']:'' }}</td>
        </tr>
        @empty
        <tr>
            <td class="text-center align-middle text-muted" colspan="9">Tidak ada presensi ditemukan.</td>
        </tr>
        @endforelse
        @if ($presensi->count() > 0)
        <tr>
            <td class="text-right align-middle font-weight-bold" colspan="4">T O T A L</td>
            <td class="text-center align-middle font-weight-bold">{{ $late->telat_totalv2 }}</td>
            <td class="text-center align-middle font-weight-bold">{{ $jam_pulang_cepat->jam_pulang_cepatv2 }}
            </td>
            <td class="text-center align-middle font-weight-bold"></td>
            <td class="text-center align-middle font-weight-bold">{{ $jam_kerja->jam_kerjav2 }}</td>
            <td class="text-center align-middle font-weight-bold"></td>
        </tr>
        @endif
        </tbody>
        </table> --}}
        <div class="pb-1 d-flex justify-content-end">
            Makassar, {{ now()->translatedFormat('d F Y') }}
            <br />
            Mengetahui, <br />
            <br>
            <br>
            <br>
            {{ $data->unitkerja->pejabat }}<br />
            {{ $data->unitkerja->nip }}
        </div>
        <div class="pt-1 text-right border-top">
            <small class="text-muted"><em>Halaman ini diakses pada tanggal
                    {{ now()->translatedFormat('d F Y H:i:s A') }}</em></small>
        </div>
    </div>


</body>

</html>
