<div id="page-feed" class="inactive">
    <div class="text-danger border border-warning alert-warning alert pt-2">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Log Presensi Harian</small>
            <br>{{ now()->translatedFormat('F Y') }}</h4>
    </div>
    <div class="block mt-3">
        <div>
            {{-- <div class="text-center">
                <div class="table-responsive">
                    <nav aria-label="Navigation">
                        <ul class="pagination">
                            @php
                                foreach ($jadwal->hari_libur as $key) {
                                    $jadwal->hari[$key-1] = 'libur';
                                }
                            @endphp
                          @foreach ($jadwal->hari as $key => $val)
                          @if ($val != 'libur')
                          <li class="page-item">
                            <button class="page-link border border-primary" value="{{ $val }}">{!! $val !!}</button>
            </li>
            @else
            <li class="page-item"><button class="page-link border border-danger text-danger" disabled><i
                        class="bx bx-x"></i></button></li>
            @endif
            @endforeach
            </ul>
            </nav>
        </div>
    </div> --}}
    {{-- <br/> --}}
    <ul class="list-unstyled">
        <li class="media border-bottom pb-1 d-flex justify-content-between align-items-center">
            <div class="media-body">
                <h5 class="mt-0 mb-1" id="date">{{ now()->translatedFormat('l, d F Y') }}</h5>
            </div>
            {{-- <a id="feed" href="javascript:void(0)" style="text-decoration: none">Log presensi</a> --}}
        </li>
        <div id="view">
            <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_masuk }}"><img
                        class="align-self-start mr-3 rounded" src="{{$f_masuk }}"
                        alt="{{ $p_masuk != null ? $p_masuk->foto:'Masuk' }}" height="50"></a>
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
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded"
                        src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="50"></a>
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
        </div>
    </ul>
    <div class="table-responsive">
        <table class="table table-sm table-borderless table-striped">
            <thead>
                <tr class="table-primary" style="font-size: 13px">
                    <td></td>
                    <th class="text-left">Masuk</th>
                    <th class="text-left">Pulang</th>
                    <th class="text-left text-danger">Terlambat</th>
                </tr>
            </thead>
            @foreach ($mypres as $key => $item)
            @php
            $masuk = $item->where('tipe',1)->first();
            $pulang = $item->where('tipe',3)->first();
            @endphp
            <tr style="font-size: 13px">
                <th class="">{{ \Carbon\Carbon::parse($key)->translatedFormat('d M') }}</th>
                <td class="text-left">{!! !empty($masuk) ?
                    \Carbon\Carbon::parse($masuk->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<small
                        class="text-muted">Belum Check Lock</small>':'<small class="text-danger">Tidak Check
                        Lock</small>') !!}</td>
                <td class="text-left">{!! !empty($pulang) ?
                    \Carbon\Carbon::parse($pulang->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<small
                        class="text-muted">Belum Check Lock</small>':'<small class="text-danger">Tidak Check
                        Lock</small>') !!}</td>
                <td class="text-left text-danger">{{ gmdate('H:i:s', $item->sum('terlambat')) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</div>
</div>

<script>
    $('.page-link').on("click", function(){
        let val = $(this).val();
        $.get('{{ url('presensi/getlog') }}/'+val, (response)=>{
                // console.log(response)
                document.getElementById('date').innerHTML = response.status
                $("#view").html(`
                <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                    <a href="javascript:void(0)" class="viewImage" data-image="${response.f_masuk}"><img class="mr-3 rounded" src="${response.f_masuk}" alt="Masuk" height="50"></a>
                   <div class="media-body">
                     <h6 class="mt-0 mb-1">Presensi Masuk</h6>
                     <strong>${response.p_masuk}</strong>
                   </div>
                   <span class="text-danger text-right"><small>Terlambat</small>
                    <br/><span style="line-height: 8px">${response.t_masuk}</span>
                   </span>
                 </li>
                 <li class="media border-bottom pb-2 mt-2 d-flex justify-content-between align-items-center">
                    <a href="javascript:void(0)" class="viewImage" data-image="${response.f_pulang}"><img class="mr-3 rounded" src="${response.f_pulang}" alt="Pulang" height="50"></a>
                   <div class="media-body">
                     <h6 class="mt-0 mb-1">Presensi Pulang</h6>
                     <strong>${response.p_pulang}</strong>
                   </div>
                 </li>
                `);
        })
    })
</script>
