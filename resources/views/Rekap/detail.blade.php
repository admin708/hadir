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
    {{-- {{ $hari }} --}}
    <div class="container-fluid mt-5">
        <h4 class="text-uppercase text-center border-bottom pb-2">{{ $heading ?? '' }}</h4>
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
                            style="font-size: 9px" class="text-muted">Belum Check Lock</small>':'<small
                            style="font-size: 9px" class="text-danger">Tidak Check
                            Lock</small>') !!}</td>
                    <td class="text-center align-middle">{!! !empty($pulang) ?
                        \Carbon\Carbon::parse($pulang->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<small
                            style="font-size: 9px" class="text-muted">Belum Check Lock</small>':'<small
                            style="font-size: 9px" class="text-danger">Tidak Check
                            Lock</small>') !!}</td>
                    <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('terlambat')) }}</td>
                    <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('jam_pulang_cepat')) }}</td>
                    {{-- <td class="text-center align-middle">{{ $pulang['pulang_cepat'] ? $p->diff($cp)->format('%Hh%Im'):'-' }}
                    </td> --}}
                    <td class="text-center align-middle">-</td>
                    <td class="text-center align-middle">{{ gmdate('H:i', $item->sum('jam_kerja')) }}</td>
                    {{-- <td class="text-center align-middle">{{ $p->diff($m)->format('%Hh%Im') }}</td> --}}
                    <td class="align-middle">{{ $pulang['pulang_cepat'] ? $pulang['catatan']:'' }}</td>
                </tr>
                @empty
                <tr>
                    <td class="text-center align-middle text-muted" colspan="9">Tidak ada presensi ditemukan.</td>
                </tr>
                @endforelse
                @if ($presensi->count() > 0)
                <tr>
                    <td class="align-middle text-right font-weight-bold" colspan="4">T O T A L</td>
                    <td class="text-center align-middle font-weight-bold">{{ $late->telat_totalv2 }}</td>
                    <td class="text-center align-middle font-weight-bold">{{ $jam_pulang_cepat->jam_pulang_cepatv2 }}
                    </td>
                    <td class="text-center align-middle font-weight-bold"></td>
                    <td class="text-center align-middle font-weight-bold">{{ $jam_kerja->jam_kerjav2 }}</td>
                    <td class="text-center align-middle font-weight-bold"></td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-end pb-1">
            Makassar, {{ now()->translatedFormat('d F Y') }}
            <br />
            Mengetahui, <br />
            <br>
            <br>
            <br>
            {{ $data->unitkerja->pejabat }}<br />
            {{ $data->unitkerja->nip }}
        </div>
        <div class="text-right border-top pt-1">
            <small class="text-muted"><em>Halaman ini diakses pada tanggal
                    {{ now()->translatedFormat('d F Y H:i:s A') }}</em></small>
        </div>
    </div>


</body>

</html>
