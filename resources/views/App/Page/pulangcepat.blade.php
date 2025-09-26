<div id="page-home" class="active">

    <h5 style="line-height: 1px" class="pb-3 text-danger text-capitalize">{{ auth()->user()->name }}</h5>
    <span class="text-dark mb-4 text-capitalize"
        style="font-size:15px; line-height: 1px">{{ \Str::words(auth()->user()->unitkerja->nama, 3).' | '.auth()->user()->jadwal->nama }}</span>
    {{-- <div class="text-danger border border-warning alert-warning alert pt-1">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Presensi
                {{ now()->translatedFormat('l') }}</small> <br>{{ now()->translatedFormat('d F Y') }} <span
        class="float-right text-dark" id="time">{{ now()->translatedFormat('H:i:s') }}</span></h4>
</div> --}}

{{-- @dd($jadwal) --}}
<div class="block mt-2">
    <div class="text-info border border-info alert-info text-center alert pt-3">
        <h6 class="font-weight-normal">Izin Pulang Cepat</h6>
    </div>
    <script>
        var isactive = 1;
    </script>
    @include('App.Page.muncul', ['boolean' => $boolean])
</div>
</div>
