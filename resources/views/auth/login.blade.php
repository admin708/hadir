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

    @laravelPWA
    {{-- @livewireStyles --}}
    @stack('styles')
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-danger" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-10 mt-5">
        <div class="login-form mt-5">
            <div class="section">
                <img src="{{ asset('') }}assets/img/sample/photo/login.svg" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Get started</h1>
                <h4>Sistem Informasi Kehadiran
                Pegawai<br>{{ config('app.name') }}</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" id="email1" name="login" value="{{ old('username') ?? old('email') }}" placeholder="Username" required autofocus autocomplete="username">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                            @if ($errors->has('username') || $errors->has('email'))
                                <div class="invalid-feedback text-left">{{ $errors->first('username') ?: $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password1" placeholder="Password" name="password" required autocomplete="current-password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                            @error('password')
                            <div class="invalid-feedback text-left"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : 'checked' }}>
                                <label class="custom-control-label" for="remember">Ingat saya</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : 'checked' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Ingat saya') }}
                                </label>
                            </div> --}}
                        </div>
                    </div>

                    {{-- <div class="form-links mt-2">
                        <div>
                            <a href="page-register.html">Register Now</a>
                        </div>
                        <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
                    </div> --}}

                    <div class="form-button-group">
                        <button type="submit" class="btn btn-danger btn-block btn-lg">Log in</button>
                    </div>

                </form>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->



    <!-- ///////////// Js Files ////////////////////  -->
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


</body>

</html>
