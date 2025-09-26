@extends('layouts.app')
@push('style-custom')
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
<style>
    #my_camera{
     width: 100%;
     height: 400px;
     /* border-radius: 50px; */
    }
    </style>
@endpush
@section('content')
@php
$sent = auth()->user()->isHadirToday()->count();
$user = auth()->user();
$date = today()->addMonths(0);
$dateDay = $date;//use your date to get month and year
$year = $dateDay->year;
$month = $dateDay->month;
$days = $dateDay->daysInMonth;
$today = $dateDay->day;
$getHariLibur = \App\Libur::getHariLibur($month, $year);
// dd($getHariLibur);
$hari = collect(range(1, $days));
$workDays=collect();
$weekEnd=collect();
// dd($weekEnd);
foreach (range(1, $days) as $day) {
    $date = \Carbon\Carbon::createFromDate($year, $month, $day);
    if ($date->isSunday() === false && $date->isSaturday() === false) {
        $workDays[]=($date->day);
    }else{
        $weekEnd[] = ($date->day);
    }
}

$libur = collect($getHariLibur != [] ? $weekEnd->merge(json_decode($getHariLibur))->sort()->unique():$weekEnd);
$realWorkDays = $hari->count()-$libur->count();
// dd($libur->all());
$has = $libur->contains($today);
$hass = \Str::contains($libur, $today);

$hadirku = $user->whereId($user->id)->with(['kehadiranku'=>function($query) use ($date){
        $query->whereMonth('created_at', $date);
    }])->withCount(['kehadiranku' => function($query) use ($date){
        $query->whereMonth('created_at', $date);
    }])->first();

/**
 * Membuat kondisi jika hari libur absen ditutup
 *
 *
 */
@endphp
<div class="container{{ $sent > 0  ? '-fluid':''}}">
    <div class="row justify-content-center">
        <div class="col-md-{{ $sent > 0 ? '10':'8'}}">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    {{-- @can('admin')
                            <span class="btn btn-success">Admin</span>
                        @elsecan('pimpinan')
                            <span class="btn btn-info">Manager</span>
                        @else
                            <span class="btn btn-warning">Pegawai</span>
                        @endcan --}}

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                       @if ($sent > 0)
                            <div class="border border-primary alert text-center">
                                <span class="lead text-primary align-middle">Anda telah mengirim kehadiran hari ini.<br/>
                                Waktu sekarang <span id="timer">@include('waktuSekarang')</span></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" rowspan="2">Nama</th>
                                            <th class="text-center align-middle" rowspan="2">NIP</th>
                                            <th class="text-center align-middle" colspan="{{ $hari->count() }}">Bulan {{ $date->translatedFormat('F').' '.date('Y') }}</th>
                                            <th class="text-center align-middle" rowspan="2"><div style="
                                                transform: rotate(90deg)">Jumlah</div></th>
                                            <th class="text-center align-middle" rowspan="2"><div style="
                                                    transform: rotate(0deg)">%</div></th>
                                        </tr>
                                        <tr>
                                            @foreach ($hari as $item)
                                                <th class="text-center align-middle">{{ $item }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{ $hadirku->name }}</td>
                                            <td class="text-center">{{ $hadirku->email }}</td>
                                            @php
                                                foreach ($libur as $key) {
                                                    $hari[$key-1] = 'weekend';
                                                }
                                                foreach ($hadirku->kehadiranku as $key => $value) {
                                                    if ($value->confirmed) {
                                                        $hari[$value->created_at->format('d')-1] = 'hadir';
                                                    } else {
                                                        $hari[$value->created_at->format('d')-1] = 'sebagian';
                                                    }
                                                }
                                            @endphp
                                            @foreach ($hari as $key => $item)
                                            <td width="2%" class="text-center {{ $item == 'weekend' ? 'bg-danger text-white':'' }} align-middle">{!! $item == 'hadir' ? '<i style="font-size:22px" class="align-middle bx bx-check-double text-success"></i>':($item == 'sebagian' ? '<i style="font-size:22px" class="align-middle bx bx-check text-primary">':($item == 'weekend' ? '<div style="font-size:10px;transform: rotate(90deg)">Libur</div>':'<i class="align-middle bx bx-x text-danger"></i>')) !!}</td>
                                            @endforeach
                                            <td class="text-center align-middle">{{ $hadirku->kehadiranku_count }}</td>
                                            <td class="text-center align-middle">{{ round($hadirku->kehadiranku_count/$libur->count()*100, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            Keterangan:<br/>
                                <i  style="font-size:22px" class="align-middle bx bx-check-double text-success"></i> = Hadir Lengkap<br/>
                                <i  style="font-size:22px" class="align-middle bx bx-check text-primary"></i> = Absen masuk/pulang saja<br/>
                                <i class="align-middle bx bx-x text-danger"></i> = Alfa (jika telah lewat)/Belum Diisi (jika belum lewat)<br/>
                                Persentase(%) = Jumlah kehadiran dibagi (total hari-hari libur) = ({{ $realWorkDays }} hari) di bulan {{ $date->translatedFormat('F').' '.date('Y') }}<br/>
                                Hari Libur + Sabtu-Minggu = {{ collect($libur)->implode(',') }}
                       @else
                            @if (!$has)
                            <div class="border border-danger alert text-center">
                                <span class="lead text-danger align-middle">Selamat Datang, {{ auth()->user()->name }}<br/>Tunggu sampai webcam terbuka<br/>
                                    <span id="timer">@include('waktuSekarang')</span></span>
                            </div>
                            <div class="row justify-content-center">
                                <div class="rounded text-center col-sm-6 mb-2">
                                    <div class="mt-1 text-center" id="results"></div>
                                        <div id="send" class="mt-3" style="display: none">
                                            <button type="button" class="btn btn-warning" id="sendMe">Kirim Kehadiran</button>
                                            <a href="{{ route('home') }}" type="button" class="ml-1 btn btn-danger">Ganti Foto</a>
                                        </div>
                                    <div class="embed-responsive embed-responsive-3by4">
                                            <div class="text-center" id="my_camera">Memuat webcam</div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary" id="take" onclick="take_snapshot()">Ambil Foto Selfie</button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="border border-danger alert text-center">
                                <span class="lead text-danger align-middle"><span style="font-weight: bold !important">Hari libur</span>. Tidak ada absensi tersedia<br/>
                                {{ today()->translatedFormat('l, d F Y') }}</span>
                            </div>
                            @endif
                       @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-custom')
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Code to handle taking the snapshot and displaying it locally -->
@if ($sent < 1 && !$has)
<script language="JavaScript">
    Webcam.set({
			// live preview size
            // width: 400,
			// height: 240,
			// device capture size
            dest_width: 480,
            dest_height: 480,
            // final cropped size
            crop_width: 480,
            crop_height: 480,
			image_format: 'jpeg',
			jpeg_quality: 90,
            flip_horiz: true,
            enable_flash: true,
            fps: 30,
            iosPlaceholderText: 'Click here to open camera.',
		});
    Webcam.attach('#my_camera');

    // preload shutter audio clip
    var shutter = new Audio();
    shutter.autoplay = false;
    shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

    function take_snapshot() {
    // play sound effect
    shutter.play();
    // take snapshot and get image data
    Webcam.snap( function(data_uri) {
    // display results in page
    document.getElementById('results').innerHTML =
     '<img id="saveMe" class="img-fluid rounded mx-auto d-block" src="'+data_uri+'"/>';
    });

    Webcam.reset();
    $('#take').hide();
    $('#send').show();
   }


    $('#sendMe').on("click", function () {
        var action = "{{ route('hadir.store') }}";
        var _method = 'POST';
        var _token = $('meta[name="csrf-token"]').attr('content')
        let base64image = document.getElementById("saveMe").src;

        Swal.queue([{
            title: 'Kirim presensi',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim',
            showCloseButton: true,
            // text:`${action}/${_method}/${_token}`,
            // html: `<input id="pin" type="number" class="form-control form-control-lg" placeholder="Enter PIN">`,
            type:'question',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(action, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": _token
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        _method: _method,
                        _token: _token,
                        image:base64image
                    })
                })
                .then(response => response.json())
                .then((data) => {
                    console.log(data)
                    if(data.status == 200){
                        swal.insertQueueStep({
                            type: 'success',
                            title: data.pesan,
                            // html: 'Keluar otomatis dalam <b></b> milliseconds. Atau klik <b>OK</b>',
                            // timer: '1000',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                                setTimeout(() =>{
                                    window.location.reload()
                                }, 1000)
                            },
                        })
                    }
                    else{
                        Swal.insertQueueStep({
                        type: 'error',
                        text: 'Kesalahan bisa diakibatkan sistem.',
                        title: data.pesan
                        })
                    }
                })
                // .then(data => Swal.insertQueueStep(data.ip))
                .catch((e) => {
                    Swal.insertQueueStep({
                    type: 'error',
                    text: 'Kesalahan bisa diakibatkan oleh jaringan atau sistem.',
                    title: 'Whooups... Terjadi kesalahan. Coba lagi.'
                    })
                })
            }
        }])
    });
</script>
@endif
<script>
$(document).ready(function() {
    setInterval(function() {
        $("#timer").load("{{ route('waktuSekarang') }}");
    }, 1000);
});
</script>

@endpush
