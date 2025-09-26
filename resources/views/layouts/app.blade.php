<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @laravelPWA
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    <style>
        .btn-group-xs>.btn,
        .btn-xs {
            padding: .10rem .5rem;
            font-size: .6875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }
    </style>
    @stack('style-custom')
</head>

<body>
    <div id="app">
        <nav class="shadow-sm navbar sticky-top navbar-expand-md navbar-dark bg-danger">
            <div class="container-fluid col-md-10">
                <a target="" class="navbar-brand" href="{{ route('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- <ul class="mr-auto navbar-nav">
                        <li>Test</li>
                    </ul> --}}

                    <!-- Right Side Of Navbar -->
                    <ul class="ml-auto navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @canany(['pimpinan', 'pimpinan_umum'])
                                <li class="nav-item">
                                    <a class="text-white nav-link" href="{{ route('home') }}">{{ __('Back to Home') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="text-white nav-link" href="{{ route('hitung') }}">{{ __('Edit Jam Masuk') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="text-white nav-link"
                                        href="{{ route('form.import') }}">{{ __('Presensi Manual') }}</a>
                                </li>
                            @endcanany
                            {{-- @canany(['pegawai', 'admin'])
                            <li class="nav-item">
                                <a class="text-white nav-link" href="{{ route('hitung') }}">{{ __('Edit Jam Masuk') }}</a>
                            </li>
                        @endcanany --}}
                            @canany(['pimpinan', 'admin', 'pimpinan_umum'])
                                <li class="nav-item dropdown">
                                    <a id="presensi" class="text-white nav-link dropdown-toggle" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Presensi <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="presensi">
                                        <a class="dropdown-item {{ request()->segment(1) == 'presensi' ? 'active' : '' }}"
                                            href="{{ route('presensi.index') }}">
                                            Presensi Harian
                                        </a>
                                        <a class="dropdown-item {{ request()->segment(1) == 'rekap' ? 'active' : '' }}"
                                            href="{{ route('rekap.index') }}">
                                            Rekap Presensi Bulanan
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="pengaturan" class="text-white nav-link dropdown-toggle" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Pengaturan <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pengaturan">
                                        <a class="dropdown-item {{ request()->segment(1) == 'pegawai' ? 'active' : '' }}"
                                            href="{{ route('pegawai.index') }}">
                                            Data Pegawai
                                        </a>
                                        <a class="dropdown-item {{ request()->segment(1) == 'unitkerja' ? 'active' : '' }}"
                                            href="{{ route('unitkerja.index') }}">
                                            Data Unit Kerja
                                        </a>
                                        <a class="dropdown-item {{ request()->is('jadwal*') == 'jadwal' ? 'active' : '' }}"
                                            href="{{ route('jadwal.index') }}">
                                            Jadwal
                                        </a>
                                        <a class="dropdown-item {{ request()->segment(1) == 'libur' ? 'active' : '' }}"
                                            href="{{ route('libur.index') }}">
                                            Hari Libur
                                        </a>
                                        <a class="dropdown-item {{ request()->segment(1) == 'pengaturan' ? 'active' : '' }}"
                                            href="{{ route('pengaturan.index') }}">
                                            Pengaturan Umum
                                        </a>
                                    </div>
                                </li>
                            @endcanany
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="text-white nav-link dropdown-toggle" href="#"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">

            @yield('content')
        </main>
    </div>
    @livewireScripts
    {{-- <script src="{{ asset('webcamjs/webcam.min.js') }}"></script> --}}
    <script src="{{ asset('js/tagsinput.js') }}"></script>
    @stack('script-custom')
</body>

</html>
