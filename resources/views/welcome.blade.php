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
    <link rel="manifest" href="{{ asset('__manifest.json') }}">
    <link href='https://unpkg.com/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    @livewireStyles
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader no-border transparent">
        <div class="left">
        </div>
        <div class="pageTitle">
            <img src="{{ asset('') }}assets/img/logo.png" alt="logo" class="logo">
        </div>
        {{-- <div class="right">
            <a href="#" class="headerButton text-secondary">
                Skip
            </a>
        </div> --}}
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="carousel-slider owl-carousel owl-theme">
            <div class="item p-2">
                <img src="{{ asset('') }}assets/img/sample/photo/selfie.svg" alt="alt"
                    class="imaged w-100 square mb-4" style="height: 420px">
                <h2>Selfie Presensi</h2>
                <p>Sudah saatnya melakukan presensi tanpa finger print.</p>
            </div>
            <div class="item p-2">
                <img src="{{ asset('') }}assets/img/sample/photo/pwa.svg" alt="alt"
                    class="imaged w-100 square mb-4">
                <h2>PWA Ready</h2>
                <p>Dengan teknologi web modern. Sebuah web seperti aplikasi mobile.</p>
            </div>
        </div>
        @livewire('splashscreen')
    </div>
    <!-- * App Capsule -->


    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="{{ asset('') }}assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('') }}assets/js/lib/popper.min.js"></script>
    <script src="{{ asset('') }}assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('') }}assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('') }}assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <!-- Base Js File -->
    <script src="{{ asset('') }}assets/js/base.js"></script>
    @livewireScripts
    @stack('scripts')

</body>

</html>
