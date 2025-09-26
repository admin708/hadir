{{-- <div class="section full no-border" style="margin-top:-20px;">
    <div class="wide-block no-border mt-2 pt-5 pb-5 bg-danger text-center">
        <div wire:loading wire:target="showPage">
            <div class="text-center spinner-grow text-white" role="status"></div>
        </div>
    </div>
</div> --}}
@livewire('timer')
<div class="d-flex justify-content-center">
    <div id="my_photo_booth" class="">

        <div class="section mb-2">
            <button class="btn btn-block btn-secondary mb-2" onclick="Webcam.reset()" wire:click="requestPulangCepat(0, 'presensi')">Batalkan Pulang Cepat</button>
            <div class="card" wire:ignore id="my_camera"
                style="background-color: transparent !important;height:380px;width:380px">
                <div class="d-flex justify-content-center align-items-center" style="margin-top: 45%">
                    <button type="button" class="btn btn-danger" onclick="Webcam.attach('#my_camera')">
                        <i style="font-size: 25px" class='bx bx-camera'></i> &nbsp; Start Camera
                    </button>
                </div>
            </div>
        </div>
        <!-- Configure a few settings and attach camera -->
        <script language="JavaScript">
            Webcam.set({
            width: 320,
            height: 320,
            dest_width: 320,
            dest_height: 320,
            image_format: 'jpeg',
            jpeg_quality: 90,
            flip_horiz: true,
            constraints: {
                width: 320, // { exact: 320 },
                height: 320, // { exact: 180 },
                facingMode: 'user',
                frameRate: 30
            }
        });

        Webcam.on('error', function(err) {
            $('#DialogIconedError').modal('show');
        });

        Webcam.attach('#my_camera');
        </script>

        <div id="pre_take_buttons" class="text-center">
            <div>Klik untuk mengambil foto</div>
            <ion-icon size="large" wire:ignore name="arrow-down-outline"></ion-icon>
        </div>

        <!-- A button for taking snaps -->
        <div id="post_take_buttons" class="text-center" style="display:none">
            <button class="btn btn-sm btn-secondary" onClick="cancel_preview()">
                <ion-icon wire:ignore name="camera-reverse-outline"></ion-icon> Foto Ulang
            </button>
            <button class="btn btn-sm btn-light" type="button" disabled="">
                <span class="spinner-border spinner-border-sm mr-05" role="status" aria-hidden="true"></span>Presensi
                dikirim dalam&nbsp;<span id="counter">3</span>&nbsp;detik
            </button>
        </div>
    </div>

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
                        <a href="#" onclick="Webcam.attach('#my_camera');" class="btn" data-dismiss="modal">HIDUPKAN</a>
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
                        {{ now()->dayName.', '.now()->translatedFormat('d F Y H:i:s') }} Wita
                        <div class="divider mt-1 mb-1 bg-light"></div>
                        <span class="text-white">Terimakasih kami sampaikan kepada Bapak/Ibu untuk selalu mematuhi
                            protap kesehatan dengan memakai masker, jaga jarak, dan selalu cuci tangan.</span>
                    </div>
                </div>
                <div class="icon-box text-success">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
            </div>
        </div>
    </div>
    <!-- * ios style -->
</div>
