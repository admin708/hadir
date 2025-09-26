<div id="page-home" class="active">

    <h5 style="line-height: 1px" class="pb-3 text-danger text-capitalize">{{ auth()->user()->name }}</h5>
    <span class="text-dark mb-2 text-capitalize" style="font-size:15px; line-height: 1px">{{ \Str::words(auth()->user()->unitkerja->nama, 3).' | '.auth()->user()->jadwal->nama }}</span>
    <div>
        @if (count($all_jadwal) > 1)
        <form action="{{ route('home') }}" method="post">
            @csrf
            <select name="jadwal_id" id="" class="form-control form-control-sm mb-1">
                @foreach ($all_jadwal as $item)
                <option value="{{ $item->id }}" {{ $item->id == auth()->user()->jadwal_id ? 'selected':'' }}>
                    {{ $item->nama }}</option>
                @endforeach
            </select>
            <button class="btn btn-block mt-1 btn-sm btn-primary">Ganti Shift</button>
        </form>
        @endif
    </div>
    <div class="text-danger border border-warning alert-warning alert pt-1 mt-2">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Presensi
                {{ now()->translatedFormat('l') }}</small> <br>{{ now()->translatedFormat('d F Y') }} <span class="float-right text-dark" id="time">{{ now()->translatedFormat('H:i:s') }}</span></h4>
    </div>

    {{-- @dd($jadwal) --}}
    <div class="block mt-2">
    @if (!$jadwal->is_today_libur)
        @if ($now >= $jadwal->clock_in_open && $now < $jadwal->clock_out_open)
            <script>
                var isactive = 1;
            </script>
            @php
            $telat = $jadwal->late_tolerance;
                if ($now > $telat) {
                $telat = $telat->diffInSeconds($now);
                }else{
                $telat = 0;
                }
            @endphp
            @if ($jadwal->is_in_today)
                @if (!$jadwal->is_out_today)
                    <div class="border border-primary alert text-center alert-primary">
                        <p class="mt-1">Presensi Masuk berhasil. Silakan tunggu waktu presensi berikutnya.<br>
                            <span></span>Presensi Pulang dibuka mulai pukul<br>
                            {{ $jadwal->clock_out_open->format('H:i') }} sampai
                            {{ $jadwal->clock_out_close->format('H:i') }}<br />

                            <form>
                                <input type="hidden" name="pulang_cepat" value="1">
                                <button type="submit" class="btn btn-sm btn-danger mt-2">Izin Pulang Cepat</button>
                            </form>
                        </p>
                    </div>
                @endif
                <br>
                <ul class="list-unstyled">
                    <li class="media border-bottom pb-1 d-flex justify-content-between align-items-center">
                        <div class="media-body">
                            <h5 class="mt-0 mb-1">Aktifitas hari ini</h5>
                        </div>
                        {{-- <a id="feed" href="javascript:void(0)" style="text-decoration: none">Log presensi</a> --}}
                    </li>
                    <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                        <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_masuk }}"><img class="mr-3 rounded" src="{{$f_masuk }}" alt="{{ $p_masuk != null ? $p_masuk->foto:'Masuk' }}" height="50"></a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">Presensi Masuk</h6>
                            <div style="line-height: 10px" class="pb-2"><strong>{!! $p_masuk !=null ?
                                    \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s
                                    A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
                            </div>
                            <div style="line-height: 1.2; font-size: 10pt;">{{ $p_masuk['address'] }}</div>
                        </div>
                        <div class="text-danger text-right"><small>Terlambat</small>
                            <br />
                            <div style="line-height: 10px">
                                <small>{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</small></div>
                        </div>
                    </li>
                    <li class="media border-bottom mt-2 pb-2">
                        <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">Presensi
                                {{ $p_pulang['pulang_cepat'] ? 'Pulang Cepat':'Pulang' }}</h6>
                            <div style="line-height: 10px" class="pb-2"><strong>{!! $p_pulang !=null ?
                                    \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s
                                    A'):'<span class="text-muted">Belum Absen</span>' !!}</strong></div>
                            <div style="line-height: 1.2; font-size: 10pt;">{{ $p_pulang['address'] }}</div>
                            @if ($p_pulang['pulang_cepat'])
                            <div class="text-muted font-italic" style="line-height: 1.2; font-size: 10pt;">Alasan pulang
                                cepat:
                                {{ $p_pulang['catatan'] }}
                            </div>
                            @endif
                        </div>
                    </li>
                </ul>
                    @else
                        @include('App.Page.muncul')
                    @endif
                @elseif($now >= $jadwal->clock_out_open && $now <= $jadwal->clock_out_close)
                <script>
                    var isactive = 1;
                </script>
                @php
                $telat = 0;
                @endphp
                    @if ($jadwal->is_out_today)
                    <div class="border border-info alert text-info text-center">
                        <p class="mt-1">Presensi Pulang berhasil. Terimakasih atas semangatnya hari ini.</p>
                    </div>
                    <ul class="list-unstyled">
                        <li class="media border-bottom pb-1 d-flex justify-content-between align-items-center">
                            <div class="media-body">
                                <h5 class="mt-0 mb-1">Aktifitas hari ini</h5>
                            </div>
                            {{-- <a id="feed" href="javascript:void(0)" style="text-decoration: none">Log presensi</a> --}}
                        </li>
                        <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                            <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_masuk }}"><img class="mr-3 rounded" src="{{$f_masuk }}" alt="{{ $p_masuk != null ? $p_masuk->foto:'Masuk' }}" height="50"></a>
                            <div class="media-body">
                                <h6 class="mt-0 mb-1">Presensi Masuk</h6>
                                <div style="line-height: 10px" class="pb-2"><strong>{!! $p_masuk !=null ?
                                        \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s
                                        A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
                                </div>
                                <div style="line-height: 1.2; font-size: 10pt;">{{ $p_masuk['address'] }}</div>
                            </div>
                            <div class="text-danger text-right"><small>Terlambat</small>
                                <br />
                                <div style="line-height: 10px">
                                    <small>{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</small></div>
                            </div>
                        </li>
                        <li class="media border-bottom mt-2 pb-2">
                            <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                            <div class="media-body">
                                <h6 class="mt-0 mb-1">Presensi
                                    {{ $p_pulang['pulang_cepat'] ? 'Pulang Cepat':'Pulang' }}</h6>
                                <div style="line-height: 10px" class="pb-2"><strong>{!! $p_pulang !=null ?
                                        \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s
                                        A'):'<span class="text-muted">Belum Absen</span>' !!}</strong></div>
                                <div style="line-height: 1.2; font-size: 10pt;">{{ $p_pulang['address'] }}</div>
                                @if ($p_pulang['pulang_cepat'])
                                <div class="text-muted font-italic" style="line-height: 1.2; font-size: 10pt;">Alasan pulang
                                    cepat:
                                    {{ $p_pulang['catatan'] }}
                                </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                    {{-- end is_out_today  --}}
                    @else
                        @include('App.Page.muncul')
                    @endif
        @else
                <script>
                    var isactive = 0;
                </script>
                <div class="border border-danger alert text-center">
                    @if ($now < $jadwal->clock_in_open)
                        Presensi dibuka pukul <br />
                        {{ $jadwal->clock_in_open->format('H:i') }} sampai
                        {{ $jadwal->clock_in_close->format('H:i') }}<br>
                    @endif
                        <h5 class="mt-1">Silakan tunggu presensi dibuka.</h5>
                </div>
                <ul class="list-unstyled">
                    <li class="media border-bottom pb-1 d-flex justify-content-between align-items-center">
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">Aktifitas hari ini</h6>
                        </div>
                        {{-- <a id="feed" href="javascript:void(0)" style="text-decoration: none">Log presensi</a> --}}
                    </li>
                    <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                        <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_masuk }}"><img class="mr-3 rounded" src="{{$f_masuk }}" alt="{{ $p_masuk != null ? $p_masuk->foto:'Masuk' }}" height="50"></a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">Presensi Masuk</h6>
                            <div style="line-height: 10px" class="pb-2"><strong>{!! $p_masuk !=null ?
                                    \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s
                                    A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
                            </div>
                            <div style="line-height: 1.2; font-size: 10pt;">{{ $p_masuk['address'] }}</div>
                        </div>
                        <div class="text-danger text-right"><small>Terlambat</small>
                            <br />
                            <div style="line-height: 10px">
                                <small>{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</small></div>
                        </div>
                    </li>
                    <li class="media border-bottom mt-2 pb-2">
                        <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">Presensi
                                {{ $p_pulang['pulang_cepat'] ? 'Pulang Cepat':'Pulang' }}</h6>
                            <div style="line-height: 10px" class="pb-2"><strong>{!! $p_pulang !=null ?
                                    \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s
                                    A'):'<span class="text-muted">Belum Absen</span>' !!}</strong></div>
                            <div style="line-height: 1.2; font-size: 10pt;">{{ $p_pulang['address'] }}</div>
                            @if ($p_pulang['pulang_cepat'])
                            <div class="text-muted font-italic" style="line-height: 1.2; font-size: 10pt;">Alasan pulang
                                cepat:
                                {{ $p_pulang['catatan'] }}
                            </div>
                            @endif
                        </div>
                    </li>
                </ul>
        @endif
    @else {{--  end if_today_libur --}}
        <script>
            var isactive = 1;
        </script>
        <div class="border border-danger alert text-center">
            <h5>Hari libur.<br /> Presensi tidak tersedia.<br>
                {{ today()->translatedFormat('l, d F Y') }}</h5>
        </div>
    @endif
    </div>
</div>
@push('script')
<script>
    @if(!$confirmed)
    Swal.fire({
        icon: 'warning'
        , title: 'Pemberitahuan'
        , html: 'Masa demo aplikasi tinggal {{ $exp->diffInDays($now) }} hari lagi. Untuk menggunakan aplikasi secara penuh silakan lakukan aktivasi.'
        , allowOutsideClick: false
    , })
    // @if ($now < $exp)

    // @else
    // Swal.fire({
    //     icon: 'error',
    //     title: 'Masa percobaan berakhir',
    //     html: 'Untuk menggunakan aplikasi secara penuh silakan lakukan aktivasi.',
    //     allowOutsideClick: false,
    //     showCancelButton: false,
    //     showConfirmButton: false
    //     // footer: '<a href>Why do I have this issue?</a>'
    // })
    // @endif
    @endif

</script>
@endpush
