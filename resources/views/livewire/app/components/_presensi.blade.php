<div class="section full no-border" style="margin-top:-30px;">
    <div class="pb-4 bg-danger text-center">
        <p class="font-weight-bold">Sudah melakukan presensi hari ini?</p>
    </div>
</div>
<div class="section mb-2" style="margin-top: -30px">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-end" style="margin-top:-10px; margin-bottom:-10px">
            <div>
                <span class="text-capitalize" style="line-height: 10px; font-weight-normal !important">Selamat
                    @php
                        if(date("H") < 12){
                            echo "Pagi";
                        }elseif(date("H") >= 12 && date("H") < 15){
                            echo "Siang";
                        }elseif(date("H") >= 15 && date("H") < 18){
                            echo "Sore";
                        }else{
                            echo "Malam";
                        }
                    @endphp Bpk/Ibu
                    </span>
                <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                    {{ auth()->user()->name }}
                </h5>
            </div>
        </div>
    </div>
</div>
{{-- <div class="section mb-2" style="margin-top: -35px">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-end">
            <div>
                <h6 class="card-subtitle" style="line-height: 10px">Presensi Bulan ini</h6>
                <h5 style="line-height: 15px" class="card-title mb-0 d-flex align-items-center justify-content-between">
                    {{ now()->translatedFormat('F Y') }}
                </h5>
            </div>
        </div>
    </div>
</div> --}}

<div class="section mt-2">
    <div class="card">
        <ul class="listview flush transparent simple-listview image-listview media">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <div class="text-muted">Hari ini</div>
                            <strong style="font-size: 18px; font-weight:bold">{{ now()->translatedFormat('l, d F Y') }}</strong>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div data-toggle="modal" data-target="#FotoMasuk" class="item">
                    <div class="imageWrapper align-self-start">
                        <img src="{{ $f_masuk }}" alt="image" class="imaged w64">
                    </div>
                    <div class="in">
                        <div>
                            <strong>Presensi Masuk</strong>
                            <div class="">
                                <ion-icon wire:ignore name="time-outline"></ion-icon> {!! $p_masuk !=null ?
                                \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s'):'<span class="text-muted"> Belum Absen </span>' !!}
                            </div>

                            <div class="text-muted">
                                <ion-icon wire:ignore name="map-outline"></ion-icon> {{ isset($p_masuk['address']) ? config('app.name'):'' }}
                                {{-- <ion-icon wire:ignore name="map-outline"></ion-icon> {{ $p_masuk['address'] }} --}}
                            </div>
                        </div>
                        <div class="align-self-start text-right">
                            <small>Terlambat</small>
                            <div class="text-danger">{{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div data-toggle="modal" data-target="#FotoPulang" class="item">
                    <div class="imageWrapper align-self-start">
                        <img src="{{ $f_pulang }}" alt="image" class="imaged w64">
                    </div>
                    <div class="in">
                        <div>
                            <strong>Presensi Pulang</strong>
                            <div class=""><ion-icon wire:ignore name="time-outline"></ion-icon> {!! $p_pulang !=null ?
                            \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s'):'<span class="text-muted"> Belum Absen </span>' !!}</div>
                            <div class="text-muted">
                                <ion-icon wire:ignore name="map-outline"></ion-icon> {{ isset($p_pulang['address']) ? config('app.name'):'' }}
                                {{-- <ion-icon wire:ignore name="map-outline"></ion-icon> {{ $p_pulang['address'] }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            @if (isset($p_pulang['pulang_cepat']))
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <strong>Alasan Pulang Cepat</strong>
                                <p class="card-text text-secondary">{{ $p_pulang->catatan }}</p>
                            </div>
                        </div>
                    </div>
                </li>
            @elseif($jadwal->is_in_today)
                @if (now() < $jadwal->clock_out_open)
                    <li>
                        <div class="item">
                            <button class="btn btn-block btn-{{ $pulang_cepat ? 'secondary':'danger' }}"
                                wire:click="requestPulangCepat('{{ $pulang_cepat ? 0:1 }}', '{{ $pulang_cepat ? 'presensi':'home' }}')">{{ $pulang_cepat ? 'Batalkan Izin Pulang Cepat':'Izin Pulang Cepat' }}</button>
                        </div>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>

<div class="section mt-1 mb-2">
    <a href="#" class="section-title justify-content-end text-primary" data-toggle="modal" data-target="#showAll">Tampilkan semua</a>
    <div class="card">
        <div class="d-flex justify-content-between align-items-end">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Masuk</th>
                            <th scope="col">Pulang</th>
                            <th scope="col" class="text-danger">Terlambat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mypres->take(3) as $key => $item)
                            @php
                                $masuk = $item->where('tipe',1)->first();
                                $pulang = $item->where('tipe',3)->first();
                            @endphp

                            <tr class="{{ $masuk['auto_presensi'] && $pulang['auto_presensi'] ? 'table-danger':'' }}">
                                <th scope="row">{{ \Carbon\Carbon::parse($key)->translatedFormat('d M') }}</th>
                                <td>{!! !empty($masuk) ?
                                $masuk['auto_presensi'] ? '<span class="text-danger">'.$masuk->statusPresensi->nama.'</span>':
                                    \Carbon\Carbon::parse($masuk->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<span
                                        class="text-muted">Belum absen</span>':'<span class="text-danger">Tidak absen</span>') !!}</td>
                                <td>{!! !empty($pulang) ?
                                $pulang['auto_presensi'] ? '<span class="text-danger">'.$pulang->statusPresensi->nama.'</span>':
                                \Carbon\Carbon::parse($pulang->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<span class="text-muted">Belum absen</span>':'<span class="text-danger">Tidak absen</span>') !!}</td>
                                <td class="text-danger">{{ gmdate('H:i:s', $item->sum('terlambat')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- bottom right -->
<div class="fab-button animate bottom-right" style="margin-bottom: 50px;">
    <a href="#" wire:click="showPage('home')" class="fab">
        <ion-icon wire:ignore name="camera-outline"></ion-icon>
    </a>
</div>
<!-- * bottom right -->

<!-- Modal Basic -->
<div class="modal fade modalbox" id="showAll" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kehadiran Bulan {{ now()->translatedFormat('F Y') }}</h5>
                <a href="javascript:;" data-dismiss="modal">Keluar</a>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Pulang</th>
                                <th scope="col" class="text-danger">Terlambat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mypres->sortKeys() as $key => $item)
                            @php
                            $masuk = $item->where('tipe',1)->first();
                            $pulang = $item->where('tipe',3)->first();
                            @endphp

                            <tr class="{{ $masuk['auto_presensi'] && $pulang['auto_presensi'] ? 'table-danger':'' }}">
                                <th scope="row">{{ \Carbon\Carbon::parse($key)->translatedFormat('d M') }}</th>
                                <td>
                                    {!! !empty($masuk) ?
                                    $masuk['auto_presensi'] ? '<span class="text-danger">'.$masuk->statusPresensi->nama.'</span>':
                                    \Carbon\Carbon::parse($masuk->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<span
                                        class="text-muted">Belum absen</span>':'<span class="text-danger">Tidak absen</span>')

                                    !!}
                                </td>
                                <td>{!! !empty($pulang) ?
                                $pulang['auto_presensi'] ? '<span class="text-danger">'.$masuk->statusPresensi->nama.'</span>':
                                    \Carbon\Carbon::parse($pulang->waktu)->format('H:i'):(now()->format('Y-m-d') == $key ? '<span
                                        class="text-muted">Belum absen</span>':'<span class="text-danger">Tidak absen</span>') !!}
                                </td>
                                <td class="text-danger">{{ gmdate('H:i:s', $item->sum('terlambat')) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Modal Basic -->

{{-- <!-- iOS Add to Home Action Sheet -->
<div class="modal inset fade action-sheet ios-add-to-home" id="ios-add-to-home-screen" tabindex="-1"
    role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add to Home Screen</h5>
                <a href="javascript:;" class="close-button" data-dismiss="modal">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content text-center">
                    <div class="mb-1"><img src="assets/img/icon/192x192.png" alt="image" class="imaged w48">
                    </div>
                    <h4>Mobilekit</h4>
                    <div>
                        Install Mobilekit on your iPhone's home screen.
                    </div>
                    <div>
                        Tap <ion-icon name="share-outline"></ion-icon> and Add to homescreen.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * iOS Add to Home Action Sheet -->
<!-- Android Add to Home Action Sheet -->
<div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
    role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add to Home Screen</h5>
                <a href="javascript:;" class="close-button" data-dismiss="modal">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content text-center">
                    <div class="mb-1"><img src="assets/img/icon/192x192.png" alt="image" class="imaged w48">
                    </div>
                    <h4>Mobilekit</h4>
                    <div>
                        Install Mobilekit on your Android's home screen.
                    </div>
                    <div>
                        Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Android Add to Home Action Sheet --> --}}


<!-- Dialog Image -->
<div class="modal fade dialogbox" id="FotoMasuk"  tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img src="{{ $f_masuk }}" alt="image" class="img-fluid">
            {{-- <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-block btn-text-secondary" data-dismiss="modal">CLOSE</a>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<div class="modal fade dialogbox" id="FotoPulang" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img src="{{ $f_pulang }}" alt="image" class="img-fluid">
            {{-- <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-block btn-text-secondary" data-dismiss="modal">CLOSE</a>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<!-- * Dialog Image -->


