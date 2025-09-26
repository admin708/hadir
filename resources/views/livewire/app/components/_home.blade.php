@livewire('timer')
{{-- cek jika sedang cuti --}}
@if (auth()->user()->status_pegawai === 2)
    <div class="section mb-2 mt-5">
        <div class="card">
            <div class="card-body d-flex justify-content-center align-items-center text-center">
                <div>
                    <div style="font-size:70px ">
                        <ion-icon wire:ignore name="alert-circle" class="text-warning mb-2"></ion-icon>
                    </div>
                    <blockquote class="blockquote mb-0">
                        <p class="lead">Status Anda sedang cuti. <br />Tidak dapat melakukan absensi.<br />
                            <small>Silakan
                                tekan tombol dibawah ini jika status cuti Anda telah berakhir.</small>
                        </p>
                    </blockquote>
                    <h5 style="line-height: 15px" class="card-title mb-0">
                        <form action="{{ route('selesai.cuti') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-lg btn-primary">Akhiri Status Cuti</button>
                        </form>

                    </h5>
                </div>
            </div>
        </div>
    </div>
@elseif(auth()->user()->status_pegawai === 0)
    <div class="section mb-2 mt-5">
        <div class="card">
            <div class="card-body d-flex justify-content-center align-items-center text-center">
                <div>
                    <div style="font-size:70px ">
                        <ion-icon wire:ignore name="alert-circle" class="text-danger mb-2"></ion-icon>
                    </div>
                    <blockquote class="blockquote mb-0">
                        <p class="lead">Status Anda bukan lagi sebagai pegawai Fakultas Hukum Unhas<br />
                            <small>Anda tidak dapat melakukan presensi lagi.</small>
                        </p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- end sedang cuti --}}
    @if (!$jadwal->is_today_libur)
        <!-- Cek hari libur -->
        @if (now()->gte($jadwal->clock_in_open) && now()->lt($jadwal->clock_out_open))
            <!-- Cek waktu presensi masuk dibuka -->
            @if ($jadwal->is_in_today)
                <!-- Cek jika sudah presensi masuk -->
                @if ($jadwal->is_out_today)
                    <div class="section mb-2 mt-5">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center text-center">
                                <div>
                                    <ion-icon wire:ignore size="large" class="text-success"
                                        name="checkmark-circle-outline">
                                    </ion-icon>
                                    <blockquote class="blockquote mb-0">
                                        <p class="lead">Presensi Pulang Cepat berhasil. <br />Terimakasih atas
                                            semangatnya
                                            hari ini.</p>
                                    </blockquote>
                                    <h6 class="card-subtitle text-capitalize mt-1" style="font-size:16px">
                                        <span class="text-danger font-weight-bold">Presensi Masuk dibuka</span>
                                    </h6>
                                    <h5 style="line-height: 20px" class="card-title mb-0">
                                        Pukul {{ $jadwal->clock_in_open->format('H:i') }} -
                                        {{ $jadwal->clock_in_close->format('H:i') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="section mb-2 mt-5">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center text-center">
                                <div>
                                    <ion-icon wire:ignore size="large" class="text-success"
                                        name="checkmark-circle-outline"></ion-icon>
                                    <blockquote class="blockquote mb-0">
                                        <p class="lead">Presensi Masuk berhasil. <br />Selamat bekerja.</p>
                                    </blockquote>
                                    <h6 class="card-subtitle text-capitalize mt-1" style="font-size:16px"><span
                                            class="text-danger font-weight-bold">Presensi Pulang dibuka</span></h6>
                                    <h5 style="line-height: 20px" class="card-title mb-0">
                                        Pukul {{ $jadwal->clock_out_open->format('H:i') }} -
                                        {{ $jadwal->clock_out_close->format('H:i') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                @include('livewire.app.components._camera')
            @endif <!-- End Cek jika sudah presensi masuk -->
        @elseif(now()->gte($jadwal->clock_out_open) && now()->lte($jadwal->clock_out_close))
            @if ($jadwal->is_out_today)
                <div class="section mb-2 mt-5">
                    <div class="card">
                        <div class="card-body d-flex justify-content-center align-items-center text-center">
                            <div>
                                <ion-icon wire:ignore size="large" class="text-success"
                                    name="checkmark-circle-outline">
                                </ion-icon>
                                <blockquote class="blockquote mb-0">
                                    <p class="lead">Presensi Pulang berhasil. <br />Terimakasih atas semangatnya hari
                                        ini.
                                    </p>
                                </blockquote>
                                <h6 class="card-subtitle text-capitalize mt-1" style="font-size:16px">
                                    <span class="text-danger font-weight-bold">Presensi Masuk dibuka</span>
                                </h6>
                                <h5 style="line-height: 20px" class="card-title mb-0">
                                    Pukul {{ $jadwal->clock_in_open->format('H:i') }} -
                                    {{ $jadwal->clock_in_close->format('H:i') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @include('livewire.app.components._camera')
            @endif
        @else
            <div class="section mb-2 mt-5">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center text-center">
                        <div>
                            <ion-icon wire:ignore size="large" class="text-danger" name="information-circle-outline">
                            </ion-icon>
                            <blockquote class="blockquote mb-0">
                                <p class="lead">Presensi Masuk belum dimulai.
                                    <br>Presensi dibuka mulai
                                </p>
                            </blockquote>
                            <h5 style="line-height: 20px" class="card-title mb-0">
                                Pukul {{ $jadwal->clock_in_open->format('H:i') }}
                                {{-- -{{ $jadwal->clock_in_close->format('H:i') }} --}}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif <!-- End Cek waktu presensi masuk dibuka -->
    @else
        <!-- Cek hari libur -->
        <div class="section mb-2 mt-5">
            <div class="card">
                <div class="card-body d-flex justify-content-center align-items-center text-center">
                    <div>
                        <div style="font-size:70px ">
                            <ion-icon wire:ignore name="close-circle" class="text-danger mb-2"></ion-icon>
                        </div>
                        <blockquote class="blockquote mb-0">
                            <p class="lead">Hari Libur. <br />Tidak ada presensi hari ini</p>
                        </blockquote>
                        <h5 style="line-height: 15px" class="card-title mb-0">
                            {{ today()->translatedFormat('l, d F Y') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    @endif <!-- End Cek hari libur -->
@endif


<!-- DialogIconedDanger -->
<div class="modal fade dialogbox" id="DialogIconedDanger" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-danger">
                <ion-icon wire:ignore name="close-circle"></ion-icon>
            </div>
            <div class="modal-header">
                {{-- <h5 class="modal-title">Error</h5> --}}
            </div>
            <div class="modal-body">
                Mungkin terjadi kesalahan. Silakan coba lagi.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#"
                        onclick="Webcam.unfreeze();document.getElementById('post_take_buttons').style.display = 'none';"
                        class="btn" data-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * DialogIconedDanger -->

<div class="modal fade dialogbox" id="DialogIconedError" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-danger">
                <ion-icon wire:ignore name="camera-outline"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Kamera Anda belum dihidupkan.</h5>
            </div>
            <div class="modal-body">
                Silakan hidupkan camera Anda.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" onclick="Webcam.attach('#my_camera');" class="btn"
                        data-dismiss="modal">HIDUPKAN</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ios style-->
<div id="notification-10" class="notification-box">
    <div class="notification-dialog ios-style">
        <div class="notification-header">
            <div class="in">
                <img src="https://ui-avatars.com/api/?background=1E74FD&color=fff&name={{ auth()->user()->name }}"
                    alt="image" class="imaged w24 rounded">
                <strong>{{ auth()->user()->name }}</strong>
            </div>
            <div class="right">
                <span>just now</span>
                <a href="#" onclick="window.location.reload()" class="close-button">
                    <ion-icon name="close-circle"></ion-icon>
                </a>
            </div>
        </div>
        <div class="notification-content">
            <div class="in">
                <h3 class="subtitle">Berhasil melakukan Presensi</h3>
                <div class="text">
                    {{ now()->dayName . ', ' . now()->translatedFormat('d F Y H:i:s') }} Wita
                    <div class="divider mt-1 mb-1 bg-light"></div>
                    <span class="text-white">Terimakasih kami sampaikan kepada Bapak/Ibu untuk selalu mematuhi protap
                        kesehatan dengan memakai masker, jaga jarak, dan selalu cuci tangan.</span>
                </div>
            </div>
            <div class="icon-box text-success">
                <ion-icon wire:ignore name="checkmark-circle-outline"></ion-icon>
            </div>
        </div>
    </div>
</div>
<!-- * ios style -->
