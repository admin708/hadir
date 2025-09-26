<div id="page-home" class="active">

    <h5 style="line-height: 1px" class="pb-3 text-danger text-capitalize">{{ auth()->user()->name }}</h5>
    {{-- <h3 style="line-height: 30px" class="pb-3 text-{{ $color }} font-weight-bold text-capitalize">Selamat {{ $harini }}</h3> --}}
    <span class="text-dark mb-4 text-capitalize" style="font-size:15px; line-height: 1px">{{ \Str::words(auth()->user()->unitkerja->nama, 3).' | '.auth()->user()->jadwal->nama }}</span>
        <div class="text-danger border border-warning alert-warning alert pt-1">
            <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Presensi {{ now()->translatedFormat('l') }}</small> <br>{{ now()->translatedFormat('d F Y') }} <span class="float-right text-dark" id="time">{{ now()->translatedFormat('H:i:s') }}</span></h4>
        </div>
     <div class="block mt-2">
    @if (!$jadwal->is_today_libur)
    {{-- @include('App.Page.muncul', ['tipe' => 3, 'nama' => 'Pulang']) --}}
    @if ($now >= $jadwal->clock_in_open && $now <= $jadwal->clock_in_close)
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
        <div class="border border-info alert text-center alert-primary">
            <h6 class="mt-1">Presensi Masuk berhasil. <br> Silakan tunggu waktu presensi berikutnya.<br><br>
            Presensi Pulang dibuka mulai pukul<br>
            {{ $jadwal->clock_out_open->format('H:i') }} sampai {{ $jadwal->clock_out_close->format('H:i') }}
        </h6>
        </div>
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
                 <strong>{!! $p_masuk !=null ? \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
               </div>
               <span class="text-danger text-right"><small>Terlambat</small>
                <br/><span style="line-height: 8px">{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</span>
               </span>
             </li>
            <li class="media border-bottom mt-2 pb-2">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                <div class="media-body">
                  <h6 class="mt-0 mb-1">Presensi Pulang</h6>
                  <strong>{!! $p_pulang !=null ? \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">Belum Absen</span>' !!}</strong><br/>
                  {{-- <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_pulang !=null ? $p_pulang->terlambat:0) }}</span> --}}
                </div>
            </li>
        </ul>
        @else
        @include('App.Page.muncul', ['tipe' => 1, 'nama' => 'Masuk'])
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
            <h5 class="mt-1">Presensi pulang berhasil. Terimakasih atas semangatnya hari ini.</h5>
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
                 <strong>{!! $p_masuk !=null ? \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
               </div>
               <span class="text-danger text-right"><small>Terlambat</small>
                <br/><span style="line-height: 8px">{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</span>
               </span>
             </li>
            <li class="media border-bottom mt-2 pb-2">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                <div class="media-body">
                  <h6 class="mt-0 mb-1">Presensi Pulang</h6>
                  <strong>{!! $p_pulang !=null ? \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">Belum Absen</span>' !!}</strong><br/>
                  {{-- <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_pulang !=null ? $p_pulang->terlambat:0) }}</span> --}}
                </div>
            </li>
        </ul>
        @else
        @include('App.Page.muncul', ['tipe' => 3, 'nama' => 'Pulang'])
        @endif
    @else
    <script>
        var isactive = 0;
    </script>
    <div class="border border-danger alert text-center">
        @if ($now < $jadwal->clock_in_open)
            Presensi dibuka pukul <br/>
            {{ $jadwal->clock_in_open->format('H:i') }} sampai {{ $jadwal->clock_in_close->format('H:i') }}<br>
            {{-- Jam Masuk pukul {{ \Carbon\Carbon::parse($masuk)->format('H:i:s') }} sampai {{ \Carbon\Carbon::parse($masuk)->addMinutes($set->toleransi_terlambat)->format('H:i:s') }} --}}
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
                 <strong>{!! $p_masuk !=null ? \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted"> Belum Absen </span>' !!}</strong>
               </div>
               <span class="text-danger text-right"><small>Terlambat</small>
                <br/><span style="line-height: 8px">{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</span>
               </span>
             </li>
            <li class="media border-bottom mt-2 pb-2">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
                <div class="media-body">
                  <h6 class="mt-0 mb-1">Presensi Pulang</h6>
                  <strong>{!! $p_pulang !=null ? \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">Belum Absen</span>' !!}</strong><br/>
                  {{-- <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_pulang !=null ? $p_pulang->terlambat:0) }}</span> --}}
                </div>
            </li>
        </ul>
    @endif
    @else
    <script>
        var isactive = 1;
    </script>
        <div class="border border-danger alert text-center">
            <h5>Hari libur.<br/> Presensi tidak tersedia.<br>
                {{ today()->translatedFormat('l, d F Y') }}</h5>
        </div>
    @endif
     </div>
</div>

