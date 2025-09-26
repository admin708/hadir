<div id="page-feed" class="inactive">
    {{-- {{ $masuk_close }} --}}
    @if (!$tglmerah)
    {{-- @include('App.Page.muncul', ['tipe' => 3, 'nama' => 'Pulang']) --}}
    @if ($now >= $masuk_open && $now <= $masuk_close)
        <script>
            var isactive = 1;
        </script>
        @php
        $telat = \Carbon\Carbon::parse($masuk)->addMinutes($set->toleransi_terlambat);
        if ($now > $telat) {
            $telat = $telat->diffInSeconds($now);
        }else{
            $telat = 0;
        }
        @endphp
        @if ($isInToday > 0)
        <div class="border border-info alert text-info text-center">
            <h6 class="mt-1">Presensi masuk berhasil. <br> Silakan tunggu waktu presensi berikutnya.<br><br>
            Presensi Pulang dibuka mulai pukul<br>
            {{ $pulang_open->format('H:i') }} sampai {{ $pulang_close->format('H:i') }}
        </h6>
        </div>
        @else
        @include('App.Page.muncul', ['tipe' => 1, 'nama' => 'Masuk'])
        @endif
        {{-- @elseif($now >= $rehat_open && $now <= $rehat_close)
            @php
                $telat = \Carbon\Carbon::parse($rehat)->addMinutes($set->toleransi_terlambat);
                if ($now > $telat) {
                    $telat = $telat->diffInSeconds($now);
                }else{
                    $telat = 0;
                }
            @endphp
            @if ($isBreakToday > 0)
            <div class="border border-info alert text-info text-center">
                <h6 class="mt-1">Presensi istirahat berhasil. <br> Silakan tunggu waktu presensi berikutnya.<br><br>
                    Presensi Pulang dibuka mulai pukul<br>
                    {{ $pulang_open->format('H:i') }} sampai {{ $pulang_close->format('H:i') }}
                </h6>
            </div>
            @else
            @include('App.Page.muncul', ['tipe' => 2, 'nama' => 'Istirahat'])
        @endif --}}
    @elseif($now >= $pulang_open && $now <= $pulang_close)
        <script>
            var isactive = 1;
        </script>
        @php
            $telat = 0;

        @endphp
        @if ($isOutToday > 0)
        <div class="border border-info alert text-info text-center">
            <h5 class="mt-1">Presensi pulang berhasil. Terimakasih atas semangatnya hari ini.</h5>
        </div>
        @else
        @include('App.Page.muncul', ['tipe' => 3, 'nama' => 'Pulang'])
        @endif
    @else
    <script>
        var isactive = 0;
    </script>
    <div class="border border-danger alert text-center">
        @if ($now < $masuk_open)
            Presensi dibuka pukul <br/>
            {{ $masuk_open->format('H:i') }} sampai {{ $masuk_close->format('H:i') }}<br>
            Jam Masuk pukul {{ \Carbon\Carbon::parse($masuk)->format('H:i:s') }} sampai {{ \Carbon\Carbon::parse($masuk)->addMinutes($set->toleransi_terlambat)->format('H:i:s') }}
        @endif
        <h5 class="mt-1">Silakan tunggu presensi dibuka.</h5>
    </div>
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

