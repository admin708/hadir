{{-- @php
    $tip = $tipe == 1 ? 'primary':($tipe == 2 ? 'warning':'danger');
    switch ($nama) {
        case 'Masuk':
            $rentang = $jadwal->clock_in_open.'&mdash;'.$jadwal->clock_in_close;
            break;
        // case 'Istirahat':
        //     $rentang = $rehat_open->format('H:i').'&mdash;'.$rehat_close->format('H:i');
        //     break;
        default:
            $rentang = $jadwal->clock_out_open.'&mdash;'.$jadwal->clock_out_close;
            break;
    }
@endphp --}}
<div class="block text-center">
    <div id="my_photo_booth" class="row text-center">
        <div class="col-sm-12 text-center">
            <div id="my_camera"></div>
            <script language="JavaScript">
                Webcam.set({
                    width: '100%',
                    height: 400,
                    dest_width: 480,
                    dest_height: 480,
                    crop_width: 480,
                    crop_height: 480,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                    flip_horiz: true
                });

                Webcam.attach('#my_camera');
            </script>
        </div>
        <div class="col-sm-12 text-center">
            <form>
                <input type="hidden" name="tipe" value="{{ $tipe ?? 1 }}" id="tipe">
                <input type="hidden" name="telat" value="{{ $telat ?? 0 }}" id="telat">
                <input type="hidden" name="pulangcepat" value="{{ $boolean ?? 0 }}" id="pulangcepat">
                {{-- <textarea name="catatan" id="catatan" class="form-control" placeholder="Alasan pulang cepat" required></textarea> --}}
                <div id="pre_take_buttons">
                    <button type="button" class="btn btn-primary" onClick="preview_snapshot()"><i
                            class='bx bx-camera bx-lg bx-tada-hover align-middle'></i> Foto Selfie</button>
                    @if ($boolean)
                        <a type="button" class="btn btn-secondary" href="{{ route('home') }}"><i
                                class='bx bx-left-arrow bx-lg bx-tada-hover align-middle'></i> Batal</a>
                    @endif
                </div>
                <div id="post_take_buttons" style="display:none">
                    <button type="button" class="btn btn-sm btn-danger" onClick="cancel_preview()"><i
                            class='bx bx-camera bx-lg bx-tada-hover align-middle'></i> Foto ulang</button>
                    <button type="button" class="btn btn-sm btn-success"
                        title="{{ $boolean ? 'Pulang Cepat?' : 'Kirim presensi' }}" onClick="save_photo(this)"><i
                            class='bx bx-send bx-lg bx-tada-hover align-middle'></i> Kirim Presensi</button>
                </div>
            </form>
        </div>
    </div>

    <div id="results" style="display:none">
    </div>
</div>
