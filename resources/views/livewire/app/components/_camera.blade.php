<div class="d-flex justify-content-center">
    <div id="my_photo_booth" class="section">
        <div class="section text-right mb-2">
            <div class="card">
                <ul class="listview image-listview rounded">
                    <li>
                        @if ($all_jadwal->count() > 1)
                            <div class="item pt-1">
                                <div class="form-group basic pt-0 pb-0">
                                    <div class="input-wrapper">
                                        <label class="label" for="city4">Pilih Shift Kerja</label>
                                        <select wire:model="jadwal_absen" class="form-control custom-select"
                                            id="city4" required>
                                            {{-- <option value="">Pilih Shift Kerja</option> --}}
                                            @foreach ($all_jadwal as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="item pt-{{ $all_jadwal->count() > 1 ? 0 : 1 }}">
                            <div class="in pt-0">
                                <div>
                                    On/Off Camera
                                </div>
                                <div class="custom-control custom-switch">
                                    <input wire:ignore type="checkbox"
                                        onclick="$(this).val(this.checked ? Webcam.attach('#my_camera'):Webcam.reset())"
                                        checked value="true" class="custom-control-input" id="customSwitch4">
                                    <label class="custom-control-label" for="customSwitch4"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
        <div class="section mb-2 mt-5">
            <div class="card" wire:ignore id="my_camera"
                style="background-color: transparent !important;height:30px;width:380px">
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
</div>
