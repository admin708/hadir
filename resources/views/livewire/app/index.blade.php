<div id="appCapsule">
    <div class="section full no-border" style="margin-top:-20px;">
        <div class="pt-5 pb-2 mt-2 text-center bg-danger">
            {{-- @if ($page == 'presensi')
                <p class="font-weight-bold">Sudah melakukan presensi hari ini?</p>
            @endif --}}
            {{-- <div wire:loading wire:target="showPage">
                <span class="float-right">loading page...</span>
            </div> --}}
        </div>
    </div>


    @if ($pulang_cepat)
        @includeWhen($page == 'home' ? true : false, 'livewire.app.components._pulang_cepat')
    @else
        @includeWhen($page == 'home' ? true : false, 'livewire.app.components._home')
    @endif

    @includeWhen($page == 'profil' ? true : false, 'livewire.app.components._profil')
    @includeWhen($page == 'kalender' ? true : false, 'livewire.app.components._kalender')
    @includeWhen($page == 'rekap' ? true : false, 'livewire.app.components._rekap')
    @includeWhen($page == 'presensi' ? true : false, 'livewire.app.components._presensi')

    @if (auth()->user()->status_pegawai !== 0)
        <div class="appBottomMenu">
            @canany(['pimpinan', 'pimpinan_umum'])
                @include('livewire.app.components._pimpinan-menu')
            @else
                <a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('presensi')"
                    class="item {{ $page == 'presensi' ? 'active' : '' }}">
                    <div class="col" style="font-size: 12px">
                        <ion-icon wire:ignore name="home-outline"></ion-icon>
                        <strong>Home</strong>
                    </div>
                </a>
                <a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('kalender')"
                    class="item {{ $page == 'kalender' ? 'active' : '' }}">
                    <div class="col" style="font-size: 12px">
                        <ion-icon wire:ignore name="calendar-outline"></ion-icon>
                        <strong>Kalender</strong>
                    </div>
                </a>

                @if ($pulang_cepat)
                    <a id="camera" href="javascript:void(0);"
                        {{ $page == 'home' ? 'onClick=pulang_cepat_snapshot(true)' : "wire:click=showPage('home')" }}
                        class="item {{ $page == 'home' ? 'active' : '' }}">
                        <div class="col">
                            <div class="action-button large {{ $page == 'home' ? '' : 'bg-secondary' }}">
                                <img src="{{ asset('images/camera.png') }}" height="32" alt="">
                            </div>
                        </div>
                    </a>
                @else
                    <a id="camera" href="javascript:void(0);"
                        {{ $page == 'home' ? 'onClick=preview_snapshot()' : "wire:click=showPage('home')" }}
                        class="item {{ $page == 'home' ? 'active' : '' }}">
                        <div class="col">
                            <div class="action-button large {{ $page == 'home' ? '' : 'bg-secondary' }}">
                                <img src="{{ asset('images/camera.png') }}" height="32" alt="">
                            </div>
                        </div>
                    </a>
                @endif
                <a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('rekap')"
                    class="item {{ $page == 'rekap' ? 'active' : '' }}">
                    <div class="col" style="font-size: 12px">
                        <ion-icon wire:ignore name="document-text-outline"></ion-icon>
                        <strong>Rekap</strong>
                    </div>
                </a>
                <a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('profil')"
                    class="item {{ $page == 'profil' ? 'active' : '' }}">
                    <div class="col" style="font-size: 12px">
                        <ion-icon wire:ignore name="person-outline"></ion-icon>
                        <strong>Profil</strong>
                    </div>
                </a>
            @endcanany
        </div>
    @endif

    <div id="toast-4" class="toast-box toast-top">
        <div class="in">
            <ion-icon wire:ignore name="checkmark-circle" class="text-success md hydrated" role="img"
                aria-label="checkmark circle">
            </ion-icon>
            <div class="text">
                Anda beralih ke jadwal {{ $me->jadwal->nama }}
            </div>
        </div>
    </div>

    {{-- pulang cepat form --}}
    <div wire:ignore class="modal fade dialogbox" id="PulangCepatDialog" data-backdrop="static" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Pulang Cepat</h5>
                </div>
                <form id="formPulangCepat">
                    <div class="mb-2 text-left modal-body">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                {{-- <label class="label" for="alasan">Alasan Anda</label> --}}
                                <textarea id="alasan" placeholder="Tulis alasan disini..." class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <button type="button" class="btn btn-text-secondary" data-dismiss="modal"
                                onclick="Webcam.unfreeze()">CLOSE</button>
                            <button type="submit" class="btn btn-text-danger">KIRIM</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="notInPresensi" class="toast-box toast-center">
        <div class="in">
            <ion-icon wire:ignore name="close-circle" class="text-danger md hydrated" role="img"
                aria-label="close circle"></ion-icon>
            <div class="text">
                <strong>Bukan Waktu Presensi</strong>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-text-light close-button">CLOSE</button>
    </div>

    <!-- DialogIconedInfo -->
    <div class="modal fade dialogbox" id="DialogIconedInfo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon">
                    <ion-icon wire:ignore name="location-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Turn on Location</h5>
                </div>
                <div class="modal-body">
                    Nyalakan fitur lokasi Anda
                </div>
                <div class="modal-footer">
                    {{-- <deepLink app:uri="https://www.google.com" /> --}}
                    <div class="btn-inline">
                        <a href="#" class="btn" data-dismiss="modal">CLOSE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * DialogIconedInfo -->

</div>
@push('scripts')
    <script>
        window.livewire.on('page', page => {
            localStorage.setItem('currentPage', page.page ?? 'home');
        });

        window.livewire.on('toast', value => {
            toastbox('toast-4', 3000);
        });

        window.livewire.on('alert', param => {
            // alert(param.pesan);
            $('#DialogForm').modal('hide');
        });
    </script>

    <script>
        document.addEventListener("livewire:load", () => {
            let currentPage = localStorage.getItem('currentPage') == null ?
                localStorage.setItem('currentPage', 'home') :
                localStorage.getItem('currentPage');
            @this.set('page', currentPage == null ? 'home' : currentPage);

            let jamsekarang = "{{ now()->format('H:i:s') }}";
            let malam = "19:00:00";

            if (jamsekarang > malam) {
                localStorage.setItem('MobilekitDarkModeActive', 1)
            } else {
                localStorage.setItem('MobilekitDarkModeActive', 0)
            }

        });

        const redirect_home = () => {
            window.location.href = "{{ url('newhome') }}";
            localStorage.setItem('currentPage', 'home');
        }
    </script>

    <!-- Code to handle taking the snapshot and displaying it locally -->
    <script language="JavaScript">
        // preload shutter audio clip
        var shutter = new Audio();
        var timer;
        var address = {};
        var pulangcepat = false;
        var catatan = null;
        var action = "{{ route('presensi.store') }}";
        var _token = "{{ csrf_token() }}";
        var seconds = 5;
        shutter.autoplay = false;
        shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';



        const preview_snapshot = () => {
            // play sound effect

            $("#camera").css('pointer-events', 'none');
            $("#camera").css('cursor', 'default');
            try {
                shutter.currentTime = 0;
            } catch (e) {
                ;
            } // fails in IE
            shutter.play();
            Webcam.on('error', function(err) {
                // $('#DialogIconedDanger').modal('show').find('.modal-body').html(`
            //     <h3><strong>Bukan Waktu Presensi</strong></h3>
            //     Automatically close after 3 seconds
            // `);
                toastbox('notInPresensi', 3000);
            });

            save();

            // freeze camera so user can preview current frame
            Webcam.freeze();
            // swap button sets
            document.getElementById('pre_take_buttons').style.display = 'none';
            document.getElementById('post_take_buttons').style.display = '';
        }


        const pulang_cepat_snapshot = (value) => {
            pulangcepat = value;
            $('#PulangCepatDialog').modal('show');
            // play sound effect
            try {
                shutter.currentTime = 0;
            } catch (e) {
                ;
            } // fails in IE
            shutter.play();
            // save();

            // freeze camera so user can preview current frame
            Webcam.freeze();

        }

        $('#formPulangCepat').on("submit", function(e) {
            e.preventDefault();
            catatan = document.getElementById('alasan').value;
            save();
            // swap button sets
            $("#alasan").prop("disabled", true);
            document.getElementById('pre_take_buttons').style.display = 'none';
            document.getElementById('post_take_buttons').style.display = '';
        });

        const save = () => {
            Webcam.snap((data_uri) => {
                const body = JSON.stringify({
                    _method: 'POST',
                    _token: _token,
                    image: data_uri,
                    address,
                    pulangcepat,
                    catatan
                });
                timer = setTimeout(() => {
                    postForm(body)
                }, 3000);
            });
        };

        const postForm = (body) => {
            return fetch(action, {
                    method: 'POST',
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": _token
                    },
                    body
                }).then(response => response.json())
                .then((data) => {
                    clearTimeout(timer);
                    // Webcam.reset();

                    if (data.status == 200) {
                        console.log(data)
                        localStorage.setItem('currentPage', 'presensi');
                        notification('notification-10', 5000);
                        Webcam.freeze();
                        clearTimeout(timer);
                        document.getElementById('post_take_buttons').style.display = 'none';

                        setTimeout(() => {
                            @this.set('page', 'presensi');
                            @this.set('pulang_cepat', false);
                            Webcam.reset();
                            $('#PulangCepatDialog').modal('hide');
                        }, 2000);

                    } else {
                        console.log(data)
                        $('#DialogIconedDanger').modal('show').find('.modal-body').html(`
                        <div>${data.message}<br>${data.text}</div>
                    `);
                    }

                }).catch((e) => {
                    console.log(e)
                    $('#DialogIconedDanger').modal('show');
                })
        };

        const cancel_preview = () => {
            // cancel preview freeze and return to live camera view
            Webcam.unfreeze();
            clearTimeout(timer)

            // swap buttons back to first set
            document.getElementById('pre_take_buttons').style.display = '';
            document.getElementById('post_take_buttons').style.display = 'none';
        }
    </script>

    <script>
        function printme(e) {
            var target = document.getElementById('target');
            target.src = "{{ route('rekap.show', auth()->user()->username) }}?action=detail&bulan=" + e +
                "&jadwal={{ auth()->user()->jadwal_id }}";
        }
    </script>

    <!---map-->
    <script>
        function onError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Aktifkan GPS Anda.")
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Aktifkan maps Anda',
                    //     // text: 'Something went wrong!',
                    //     // footer: 'sikpegft.unhas.ac.id'
                    // });
                    // $("#DialogIconedInfo").modal('show');

                    // // alert("denied")

                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.")
                    // Swal.fire({
                    // icon: 'error',
                    // title: 'Location information is unavailable',
                    // // text: 'Something went wrong!',
                    // footer: 'sikpegft.unhas.ac.id'
                    // });
                    alert("unavailable")
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.")
                    // Swal.fire({
                    // icon: 'error',
                    // title: 'The request to get user location timed out.',
                    // // text: 'Something went wrong!',
                    // footer: 'sikpegft.unhas.ac.id'
                    // });
                    // alert("timeout")
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.")
                    // Swal.fire({
                    // icon: 'error',
                    // title: 'An unknown error occurred.',
                    // // text: 'Something went wrong!',
                    // footer: 'sikpegft.unhas.ac.id'
                    // })
                    alert("unknown")
                    break;
            }
        }

        async function onSuccess(position) {

            const mapBox = await fetch(
                `https://api.mapbox.com/geocoding/v5/mapbox.places/${position.coords.longitude},${position.coords.latitude}.json?access_token=pk.eyJ1IjoicnN1cmVnYXIiLCJhIjoiY2psdnp3cTJrMTFtczNyb2kwbDNsdWpubCJ9.NN1ZbfeBNqOJLSUkiC052w`
            );

            const mapBoxResponse = await mapBox.json();
            address.long = mapBoxResponse.query[0]
            address.lat = mapBoxResponse.query[1]
            address.place = mapBoxResponse.features[0].place_name

            // alert(mapBoxResponse.query[0]+' '+mapBoxResponse.query[1])
        }

        if (!navigator.geolocation) {
            onError();
        } else {
            navigator.geolocation.getCurrentPosition(onSuccess, onError);
        }
    </script>
@endpush
