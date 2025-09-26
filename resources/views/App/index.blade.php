@php
use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SIKPEG">
    <meta name="author" content="Rsuregar">
    <link rel="icon" href="{{ asset('images/icons/Icon-16.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @laravelPWA
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('webcamjs/webcam.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet"> --}}
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />

    <style type="text/css">
        body {
            font-family: 'Roboto', sans-serif;
        }

        h1,
        h2,
        h3 {
            margin-top: 0;
            margin-bottom: 0;
        }

        form {
            margin-top: 15px;
        }

        form input {
            margin-right: 15px;
        }

        #my_camera {
            text-align: center
        }

        video {
            width: 100% !important;
            height: 480px;
            border-radius: 50px;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>

</head>

<body>
    <!-- As a heading -->
    <nav class="navbar bg-danger navbar-dark fixed-top">
        <div class="container-fluid col-md-10">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/icons/icon-144x144.png') }}" width="30" height="30"
                    class="d-inline-block align-top" alt="" loading="lazy">
                {{ config('app.name') }}
            </a>

            <a href="{{ route('home') }}">
                <h3 class="float-right text-white"><i class="align-middle bx bx-refresh"></i></h3>
            </a>
        </div>
    </nav>


    <!-- Begin page content -->
    <div class="container" style="padding: 30px">

        @php //Home
        // dd($jadwal);
        $now = now();
        $harini = now()->translatedFormat('a');
        $color = $harini == 'sore' ? 'warning':($harini == 'pagi' ? 'primary':($harini == 'siang' ? 'danger':'dark'));
        $p_masuk = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(1)->first();
        $p_rehat = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(2)->first();
        $p_pulang = \App\Presensi::whereUserId(auth()->id())->whereDate('waktu', now()->format('Y-m-d'))
        ->whereTipe(3)->first();
        $f_masuk = $p_masuk != null ? asset('storage/foto-kehadiran/'.$p_masuk->foto):'https://via.placeholder.com/70';
        $f_rehat = $p_rehat != null ? asset('storage/foto-kehadiran/'.$p_rehat->foto):'https://via.placeholder.com/70';
        $f_pulang = $p_pulang != null ?
        asset('storage/foto-kehadiran/'.$p_pulang->foto):'https://via.placeholder.com/70';
        @endphp

        @php //Rekap
        $me = auth()->user();
        $date = now();
        $mypres = \App\Presensi::whereUserId($me->id)->whereMonth('waktu', $date->month)
        ->whereYear('waktu', $date->year)->get()
        ->groupBy(function ($val) {
        return \Carbon\Carbon::parse($val->waktu)->format('Y-m-d');
        });

        $no_in = $me->whereId($me->id)->withCount(['myPresensi as tidak_pres' => function($query) use($date){
        $query->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->whereTipe(1);
        }])->first();
        $no_out = $me->whereId($me->id)->withCount(['myPresensi as tidak_pres' => function($query) use($date){
        $query->whereMonth('waktu', $date->month)->whereYear('waktu', $date->year)->whereTipe(3);
        }])->first();

        $telat_in = $me->myPresensi()->whereMonth('waktu', today())->where('tipe', 1)->sum('terlambat');
        $telat_out = $me->myPresensi()->whereMonth('waktu', today())->where('tipe', 3)->sum('terlambat');
        $telat_total = $me->myPresensi()->whereMonth('waktu', today())->sum('terlambat');

        $boolean = $pulang_cepat;
        // dd($mypres);
        @endphp
        {{-- {{ $telat_total }} --}}
        {{-- @dd(\App\Jadwal::getLate(2)) --}}
        <!-- Home page -->
        {{-- @include('App.Page.newhomev2') --}}
        {{-- @include('App.Page.newhome') --}}

        @includeWhen($boolean, 'App.Page.pulangcepat')
        @includeUnless($boolean, 'App.Page.newhomev2')

        <!-- Feed page -->
        @include('App.Page.log')

        <!-- Create page -->
        @include('App.Page.rekap')

        <!-- Create page -->
        {{-- @include('App.Page.faq', ['data' => $set ?? []]) --}}

        <!-- Password page -->
        {{-- @include('App.Page.password', ['data' => $data ?? []]) --}}

        <!-- Account page -->
        @include('App.Page.akun')

    </div>



    {{-- <pre>Locating you...</pre> --}}


    <!-- Bottom Nav Bar -->
    <footer class="footer">
        <div class="d-flex justify-content-center">

        </div>
        <div id="buttonGroup" class="btn-group selectors btn-group-lg" role="group" aria-label="Basic example">
            <button id="home" type="button" class="btn btn-secondary button-active">
                <div class="selector-holder" style="font-size: 25px">
                    <i class='bx bx-camera bx-lg bx-tada-hover'></i>
                    <span>Presensi</span>
                </div>
            </button>
            <button id="feed" type="button" class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                    <i class='bx bx-list-ul bx-lg bx-tada-hover'></i>
                    <span>Log</span>
                </div>
            </button>
            {{-- <button id="left" type="button" class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                   <i class='bx bx-log-out bx-lg bx-tada-hover'></i>
                   <span>Leave Request</span>
                </div>
             </button> --}}
            <button id="create" type="button" class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                    <i class='bx bx-food-menu bx-lg bx-tada-hover'></i>
                    <span>Rekap</span>
                </div>
            </button>
            {{-- <button id="faq" type="button" class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                   <i class='bx bx-question-mark bx-lg bx-tada-hover'></i>
                   <span>FAQ</span>
                </div>
             </button> --}}
            <button id="account" type="button" class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                    <i class='bx bx-user bx-lg bx-tada-hover'></i>
                    <span>Akun</span>
                </div>
            </button>
            @can('pimpinan')
            <button type="button" onclick="javascript:location.href='{{ route('presensi.index') }}'"
                class="btn btn-secondary button-inactive">
                <div class="selector-holder" style="font-size: 25px">
                    <i class='bx bx-menu bx-lg bx-tada-hover'></i>
                    <span>Admin</span>
                </div>
            </button>
            @endcan
        </div>
    </footer>

    <!-- JS needed for this page -->

    <script src="{{ asset('js/main.js') }}"></script>
    <!-- Code to handle taking the snapshot and displaying it locally -->

    <script language="JavaScript">
        // preload shutter audio clip
		var shutter = new Audio();
        var address = {}
		shutter.autoplay = false;
		shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

		function preview_snapshot() {
			// play sound effect
			try { shutter.currentTime = 0; } catch(e) {;} // fails in IE
			shutter.play();

			// freeze camera so user can preview current frame
			Webcam.freeze();

			// swap button sets
			document.getElementById('pre_take_buttons').style.display = 'none';
			document.getElementById('post_take_buttons').style.display = '';
		}

		function cancel_preview() {
			// cancel preview freeze and return to live camera view
			Webcam.unfreeze();

			// swap buttons back to first set
			document.getElementById('pre_take_buttons').style.display = '';
			document.getElementById('post_take_buttons').style.display = 'none';
		}

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    @if ($boolean)
    <script>
        function save_photo(e) {
			// actually snap photo (from preview freeze) and display it
			Webcam.snap( function(data_uri) {
                let action = "{{ route('presensi.store') }}";
                let _token = "{{ csrf_token() }}";

                Swal.queue([{
            title: e.getAttribute("title"),
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim',
            showCloseButton: true,
            icon:'question',
            html:`<textarea name="catatan" id="catatan" class="form-control" placeholder="Alasan pulang cepat" required></textarea>`,
            showLoaderOnConfirm: true,
            reverseButtons: true,
            preConfirm: () => {
                var str = document.getElementById('catatan').value;
                var count = str.trim().length;
                if (count > 0) {
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
                            _method: 'POST',
                            _token: _token,
                            image:data_uri,
                            tipe: document.getElementById('tipe').value,
                            telat: document.getElementById('telat').value,
                            catatan: document.getElementById('catatan').value,
                            pulangcepat: 1,
                            address
                        })
                    }).then(response => response.json())
                     .then((data) => {
                        console.log(data)
                        if(data.status == 200){
                            swal.insertQueueStep({
                                icon: 'success',
                                title: data.pesan,
                                html: `<h6>${data.text}</h6>`,
                                allowOutsideClick: false,
                                // timer: '1000',
                                preConfirm: (isConfirm) => {
                                    Swal.showLoading()
                                    if (isConfirm) {
                                        window.location.href = "{{ url('/') }}"
                                    }
                                },
                            })
                        }
                        else{
                            Swal.insertQueueStep({
                            icon: 'error',
                            text: 'Kesalahan bisa diakibatkan sistem.',
                            title: data.pesan
                            })
                        }
                    }).catch((e) => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            text: 'Kesalahan bisa diakibatkan oleh jaringan atau sistem.',
                            title: 'Whooups... Terjadi kesalahan. Coba lagi.'
                        })
                    })
                    } else {
                        Swal.showValidationMessage('Wajib mengisi alasan cepat pulang')
                    }
            }
            }]).then((result) => {
            /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.reload()
                }
            })

			document.getElementById('results').innerHTML = '<img class="img-fluid rounded mx-auto d-block" src="'+data_uri+'"/>';
			Webcam.reset();
			document.getElementById('results').style.display = '';
			document.getElementById('my_photo_booth').style.display = 'none';
			});
        }
    </script>
    @else
    <script>
        function save_photo(e) {
            Webcam.snap( function(data_uri) {
                let action = "{{ route('presensi.store') }}";
                let _token = "{{ csrf_token() }}";

                Swal.queue([{
            title: e.getAttribute("title"),
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim',
            showCloseButton: true,
            icon:'question',
            showLoaderOnConfirm: true,
            reverseButtons: true,
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
                            _method: 'POST',
                            _token: _token,
                            image:data_uri,
                            tipe: document.getElementById('tipe').value,
                            telat: document.getElementById('telat').value,
                            address
                        })
                    }).then(response => response.json())
                    .then((data) => {
                        console.log(data)
                        if(data.status == 200){
                            swal.insertQueueStep({
                                icon: 'success',
                                title: data.pesan,
                                html: `<h6>${data.text}</h6>`,
                                allowOutsideClick: false,
                                // timer: '1000',
                                preConfirm: (isConfirm) => {
                                    Swal.showLoading()
                                    if (isConfirm) {
                                        window.location.reload()
                                    }
                                },
                            })
                        }
                        else{
                            Swal.insertQueueStep({
                            icon: 'error',
                            text: 'Kesalahan bisa diakibatkan sistem.',
                            title: data.pesan
                            })
                        }
                    }).catch((e) => {
                        Swal.insertQueueStep({
                            icon: 'error',
                            text: 'Kesalahan bisa diakibatkan oleh jaringan atau sistem.',
                            title: 'Whooups... Terjadi kesalahan. Coba lagi.'
                        })
                    })
            }
            }]).then((result) => {
            /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.reload()
                }
            })

            document.getElementById('results').innerHTML = '<img class="img-fluid rounded mx-auto d-block" src="'+data_uri+'"/>';
            Webcam.reset();
            document.getElementById('results').style.display = '';
            document.getElementById('my_photo_booth').style.display = 'none';
            });
        }
    </script>
    @endif

    <script>
        var timeDisplay = document.getElementById("time");
        function refreshTime() {
        var dateString = new Date().toLocaleString("id-ID", {timeZone: "Asia/Makassar"});
        var formattedString = dateString.split(" ")[1];
        var betulan = formattedString.split(".").join(':');
        timeDisplay.innerHTML = betulan;
        if(!isactive)
            $.get('{{ route('waktunnami') }}', (response)=>{
                    console.log(response.status)
                    if(!isactive && response.status) location.reload()
            })

            // console.log(isactive)

        }
        setInterval(refreshTime, 1000);
            $('.viewImage').on('click', function(){
                // console.log(this.getAttribute('data-image'))
                Swal.fire({
                    imageUrl: this.getAttribute('data-image'),
                    imageWidth: 400,
                    imageHeight: 400,
                    showCloseButton: true,
                    showConfirmButton: false
                })
            });
    </script>

    <script>
        function onError(error) {
            // Webcam.reset();
            switch(error.code) {
            case error.PERMISSION_DENIED:
                // alert("User denied the request for Geolocation.")
                Swal.fire({
                    icon: 'error',
                    title: 'Aktifkan maps Anda',
                    // text: 'Something went wrong!',
                    footer: 'sikpegft.unhas.ac.id'
                })
            break;
            case error.POSITION_UNAVAILABLE:
                // alert("Location information is unavailable.")
                Swal.fire({
                icon: 'error',
                title: 'Location information is unavailable',
                // text: 'Something went wrong!',
                footer: 'sikpegft.unhas.ac.id'
                })
            break;
            case error.TIMEOUT:
                // alert("The request to get user location timed out.")
                Swal.fire({
                icon: 'error',
                title: 'The request to get user location timed out.',
                // text: 'Something went wrong!',
                footer: 'sikpegft.unhas.ac.id'
                })
            break;
            case error.UNKNOWN_ERROR:
                // alert("An unknown error occurred.")
                Swal.fire({
                icon: 'error',
                title: 'An unknown error occurred.',
                // text: 'Something went wrong!',
                footer: 'sikpegft.unhas.ac.id'
                })
            break;
            }
        }

    async function onSuccess(position) {

    // Webcam.reset();
    // Webcam.attach( '#my_camera' );

      const mapBox = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${position.coords.longitude},${position.coords.latitude}.json?access_token=pk.eyJ1IjoicnN1cmVnYXIiLCJhIjoiY2psdnp3cTJrMTFtczNyb2kwbDNsdWpubCJ9.NN1ZbfeBNqOJLSUkiC052w`);

      const mapBoxResponse = await mapBox.json();

    //   console.log(mapBoxResponse.features[2].text)
        // address.textContent += `
        //     ${mapBoxResponse.query[0]}
        //     ${mapBoxResponse.query[1]}
        //     ${mapBoxResponse.features[0].place_name}`;
            address.long = mapBoxResponse.query[0]
            address.lat = mapBoxResponse.query[1]
            address.place = mapBoxResponse.features[0].place_name
        }

    if (!navigator.geolocation) {
      onError();
    } else {
      navigator.geolocation.getCurrentPosition(onSuccess, onError);

    }
    </script>

    {{-- //cek apakah memiliki lebih dari jadwal --}}



    @stack('script')
</body>

</html>
