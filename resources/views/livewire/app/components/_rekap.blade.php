<div class="section full no-border" style="margin-top:-10px;">
    <div class="bg-danger text-center">
    </div>
</div>
<div class="section mb-2" style="margin-top: -30px">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-end">
            <div>
                <h6 class="card-subtitle" style="line-height: 10px">Rekap Presensi Bulan</h6>
                <h5 style="line-height: 15px" class="card-title mb-0 d-flex align-items-center justify-content-between">
                    {{ now()->translatedFormat('F Y') }}
                </h5>
            </div>
        </div>
    </div>
</div>

<div class="section">
<div class="row">
    <div class="col-12 mb-2">
        <div class="card product-card bg-info">
            <div class="card-body text-center">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-2 mt-1 text-white">Presentasi Kehadiran</h2>
                <p class="card-title" style="font-size: 27px; margin-bottom:18px">{{ round($hadir->count()/$jadwal->hari_kerja_efektif*100, 2) }}<sup><small>%</small></sup></p>
                {{-- <div class="price text-white">kali</div> --}}
            </div>
        </div>
    </div>
    <div class="col-4 mb-2">
        <div class="card product-card bg-success">
            <div class="card-body text-center ">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Hadir</h2>
                <p class="card-title">{{ $hadir->count() }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-1">
        <div class="card product-card bg-primary">
            <div class="card-body text-center">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Izin</h2>
                <p class="card-title">{{ $izin }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-1">
        <div class="card product-card bg-warning">
            <div class="card-body text-center">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Sakit</h2>
                <p class="card-title">{{ $sakit }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>

    <div class="col-4 mb-1">
        <div class="card product-card bg-danger">
            <div class="card-body text-center">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Alpa</h2>
                <p class="card-title">{{ $alfa }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-1">
        <div class="card product-card bg-secondary">
            <div class="card-body text-center">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Cuti</h2>
                <p class="card-title">{{ $cuti ?? 0 }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-2">
        <div class="card product-card bg-dark">
            <div class="card-body text-center ">
                {{-- <img src="assets/img/sample/photo/product1.jpg" class="image" alt="product image"> --}}
                <h2 class="title mb-1 text-white">Dinas</h2>
                <p class="card-title">{{ $dinas ?? 0 }}</p>
                <div class="price text-white">kali</div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="section mt-1">
    <div class="card">
        <ul class="listview image-listview text transparent p-0 rounded">
            <li class="table-primary font-weight-bold">
                <a href="#" data-toggle="modal" data-target="#actionSheetShareBox" class="item">
                    <div class="in">
                        <div>Rekap Presensi</div>
                    </div>
                    <span class="font-weight-normal">Download</span>
                </a>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>Jumlah Pulang Cepat</div>
                    </div>
                    <span class="badge badge-primary">{{ $jumlah_pulang_cepat ?? 0 }}</span>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>Jumlah Jam Terlambat</div>
                        <span class="badge badge-danger">{{ $telat_total ?? 0 }}</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>Hari Kerja Efektif</div>
                        <span class="badge badge-primary">{{ $jadwal->hari_kerja_efektif }}</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>Hari Libur</div>
                        <span class="badge badge-warning">{{ $jadwal->hari_libur->count() }}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

<!-- Share Action Sheet -->
<div class="modal fade action-sheet inset" id="actionSheetShareBox" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Bulan Tahun {{ now()->year }}</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content text-center">
                    <div class="row">

                        @foreach (range(1,12) as $mt)
                        @php
                            // $color = collect(['primary', 'danger', 'info', 'facebook', 'youtube', 'success', 'instagram', 'twitter', 'linkedin', 'dark', 'twitch', 'whatsapp'])->random(1);
                        @endphp
                            <div class="col-2 mb-2">
                                <a href="#" onclick="printme({{ $mt }})" class="btn btn-icon btn-{{ $mt == today()->format('m') ? 'whatsapp':'linkedin' }}" data-dismiss="modal">
                                    {{ \Carbon\Carbon::createFromDate(now()->year, $mt)->translatedFormat('M') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- * Share Action Sheet -->
<iframe src="" id="target" name='target' style="display:none"></iframe>


