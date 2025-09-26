<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#EC4433">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="Webcam Presensi">
    <meta name="keywords" content="webcam, presensi" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- <link rel="manifest" href="{{ asset('__manifest.json') }}"> --}}
    <link href='https://unpkg.com/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <script src="{{ asset('webcamjs/webcam.js') }}"></script>

    @laravelPWA
    @livewireStyles
    @stack('styles')
</head>

<body>

    <!-- loader -->
    {{-- <div id="loader">
        <div class="spinner-grow text-danger" role="status"></div>
    </div> --}}
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-danger no-border justify-content-start">
        {{-- <div class="left">
            <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div> --}}
        <div class="pageTitle ml-2">
            <img src="{{ asset('/') }}assets/img/logo-white.png" alt="logo" class="logo" style="max-height: 25px">
        </div>
        <div class="right">
            <div class="custom-control custom-switch d-none">
                <input type="checkbox" class="custom-control-input dark-mode-switch custom-control-sm" id="darkmodeswitch">
                <label class="custom-control-label" for="darkmodeswitch"></label>
            </div>
            {{-- <div>
                On/Off Camera
            </div>
            <div class="custom-control custom-switch">
                <input wire:ignore type="checkbox" onclick="$(this).val(this.checked ? Webcam.attach('#my_camera'):Webcam.reset())"
                    checked value="true" class="custom-control-input" id="customSwitch4">
                <label class="custom-control-label" for="customSwitch4"></label>
            </div> --}}
        </div>
    </div>

    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="main">
        @yield('content')
    </div>
    <!-- * App Capsule -->
    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('/') }}assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('/') }}assets/js/lib/popper.min.js"></script>
    <script src="{{ asset('/') }}assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('/') }}assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('/') }}assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- Base Js File -->
    <script src="{{ asset('/') }}assets/js/base.js"></script>
    @livewireScripts
    @stack('scripts')

    {{-- <script>
        setTimeout(() => {
            notification('notification-welcome', 5000);
        }, 2000);
    </script> --}}

</body>

</html>
