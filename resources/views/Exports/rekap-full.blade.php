<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Rekap' }}</title>
</head>
<body>
    <table width="100%" >
        <tr>
            <td colspan="{{ now()->daysInMonth+7 }}">{{ $title ?? '' }}  {{ \App\Pengaturan::first()->nama_instansi }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <th align="center" rowspan="2" width="3%">#</th>
                <th align="center" rowspan="2" width="10%">NIP</th>
                <th align="center" rowspan="2" width="10%">Nama</th>
                <th align="center" rowspan="2" width="5%">Presensi</th>
                <th align="center" colspan="{{ now()->daysInMonth }}">Bulan {{ now()->format('d M Y') }}</th>
                <th align="center" rowspan="2" width="2%"><div style="
                    transform: rotate(90deg)">Total</div></th>
                <th align="center" rowspan="2" width="3%"><div style="
                        transform: rotate(90deg)">Telat</div></th>
                <th align="center" rowspan="2" width="3%"><div style="
                        transform: rotate(0deg)">%</div></th>
            </tr>
            <tr>
                @foreach (range(1, now()->daysInMonth) as $item)
                    <th align="center">{{ $item }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</body>
</html>
