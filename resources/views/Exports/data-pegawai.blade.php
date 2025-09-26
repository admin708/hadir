<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Data Pegawai' }}</title>
</head>

<body>
    <table width="100%">
        <thead>
            <tr>
                <th align="center" width="3%">#</th>
                <th align="center" width="10%">NIP</th>
                <th align="center" width="10%">Nama</th>
                <th align="center" width="5%">Unit kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ is_numeric($item->username) ? '`'.$item->username:$item->username}}</td>
                <td>{{ $item->name}}</td>
                <td>{{ optional($item->unitkerja)->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
