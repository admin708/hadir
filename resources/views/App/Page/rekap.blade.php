<div id="page-create" class="inactive">
    <div class="text-danger border border-warning alert-warning alert pt-2">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Rekap Presensi Bulan </small>
            <br>{{ now()->translatedFormat('F Y') }}
            {{-- <span class="float-right"><small> <button class="btn btn-sm btn-danger mr-1"><i class="bx bxs-file-pdf"></i> PDF</button><button class="btn btn-sm btn-success"><i class="bx bxs-file"></i> Excel</button></small></span> --}}
        </h4>
    </div>
    <div class="row justify-content-center">
        {{-- @php
            $harikerja = $telat_total/3600;
            $tidakmasuk = $harikerja/7.5;
        @endphp --}}
        {{-- {{ now() ->subDays($tidakmasuk)
            ->diff(now())
            ->format('%d hari') }} --}}
        <div class="col-6 col-sm-6 col-lg-6 mb-4">
            <a class="text-dark" style="text-decoration:none" href="javascript:void(0)">
                <div class="card border border-success">
                    <div class="card-body p-3 text-center bg-success">
                        <span class="bg-success pl-2 pr-2 rounded text-white font-weight-normal">Kehadiran</span>
                        <div class="h1 text-white m-0 mt-3 font-weight-bold">{{ $mypres->count() }}</div>
                        <div class="mb-2 text-white">kali</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-sm-6 col-lg-6 mb-4">
            <a class="text-dark" style="text-decoration:none" href="javascript:void(0)">
                <div class="card border border-danger">
                    <div class="card-body p-3 text-center bg-danger">
                        <span class="bg-danger pl-2 pr-2 rounded text-white font-weight-normal">Tidak Masuk</span>
                        <div class="h1 text-white m-0 mt-3 font-weight-bold">
                            {{ $jadwal->hari_kerja_efektif-$mypres->count() }}</div>
                        <div class="mb-2 text-white">kali</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-12 col-lg-12 mb-4">
            <a class="text-dark" style="text-decoration:none" href="javascript:void(0)">
                <div class="card border border-info">
                    <div class="card-body p-3 text-center bg-info">
                        <span class="bg-info pl-2 pr-2 rounded text-white font-weight-normal">Persentase
                            Kehadiran</span>
                        <div class="h1 text-white m-0 mt-3 font-weight-bold">
                            {{ round($mypres->count()/$jadwal->hari_kerja_efektif*100, 2) }}<sup>%</sup></div>
                        {{-- <div class="mb-3"></div> --}}
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-12 col-lg-12 mb-4">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center text-uppercase"
                    onclick="printme()" style="cursor:pointer ">
                    Download Rekap Bulan {{ now()->monthName }}
                </li>
                <li class=" list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                    HARI KERJA EFEKTIF
                    <span class="badge badge-success badge-pill">{{ $jadwal->hari_kerja_efektif }}</span>
                </li>
                <li title="{{ collect($jadwal->hari_libur)->implode(',') }}" data-toggle="tooltip" data-placement="top"
                    class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                    WEEKEND + TANGGAL MERAH
                    <span class="badge badge-warning badge-pill">{{ $jadwal->hari_libur->count() }}</span>
                </li>
                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                    TOTAL TERLAMBAT
                    <span class="badge badge-danger badge-pill">
                        {{-- {{ now()->subSeconds($telat_total)
                        ->diff(now())
                        ->format('%h jam %i menit') }} --}}
                        {{ \App\Jadwal::getLate($me->id)->telat_total }}
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Presensi Masuk
                    <span class="badge badge-success badge-pill">{{ $no_in->tidak_pres }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Presensi Pulang
                    <span class="badge badge-success badge-pill">{{ $no_out->tidak_pres }}</span>
                </li>
                {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                    Terlambat Presensi Masuk
                    <span class="badge badge-danger badge-pill">{{ gmdate('H:i:s', $telat_in) }}</span>
                </li> --}}
            </ul>
        </div>

        {{-- <div class="col-12 col-sm-12 col-lg-4 mb-4">
            <a class="text-dark" style="text-decoration:none" href="javascript:void(0)">
                <div class="card border border-dark" >
                    <div class="card-body p-3 text-center">
                        <span class="bg-dark pl-2 pr-2 rounded text-white font-weight-normal">Terlambat Presensi Masuk</span>
                    <div class="h1 m-0 mt-3 font-weight-bold">12</div>
                        <div class="mb-3">jam</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-12 col-lg-4 mb-4">
            <a class="text-dark" style="text-decoration:none" href="javascript:void(0)">
                <div class="card border border-info" >
                    <div class="card-body p-3 text-center">
                        <span class="bg-info pl-2 pr-2 rounded text-white font-weight-normal">Terlambat Presensi Istirahat</span>
                    <div class="h1 m-0 mt-3 font-weight-bold">12</div>
                        <div class="mb-3">jam</div>
                    </div>
                </div>
            </a>
        </div> --}}
    </div>
</div>
<iframe src="" id="target" name='target' style="display:none"></iframe>

<script>
    function printme(){
    var target = document.getElementById('target');
    target.src = "{{ route('rekap.show', auth()->user()->username) }}?action=detail&bulan={{ now()->month }}&jadwal={{ auth()->user()->jadwal_id }}";
    }

</script>
