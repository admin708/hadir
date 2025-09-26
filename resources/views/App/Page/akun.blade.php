
<div id="page-account" class="inactive">
    <div class="text-danger border border-warning alert-warning alert pt-2">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Detail akun</small> <br>{{ \Str::words(auth()->user()->name, 4) }}</h4>
    </div>
    <div class="block mt-3 text-center">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span class="text-muted">Nama</span>
              <span class="" data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->name }}">{{ \Str::words(auth()->user()->name, 4) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">NIK/NIP</span>
                <span class="">{{ auth()->user()->username }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">Email</span>
                <span class="">{{ auth()->user()->email }}</span>
            </li>
            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">Status</span>
                <span class="text-capitalize">{{ auth()->user()->role_id }}</span>
            </li> --}}
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">Unit Kerja</span>
                <span class="text-capitalize" data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->unitkerja->nama }}">{{ \Str::words(auth()->user()->unitkerja->nama, 2) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">Ganti Password</span>
                <a href="javascript:void(0)" id="password"><span class="badge badge-danger">Klik disini</span></a>
            </li>
            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="text-muted">Hard reload</span>
                <span class="badge badge-danger" onClick="window.location.reload(true)">Hot Reload</span>
            </li> --}}
        </ul>
        <div class="mt-5 mb-5">
            <a style="text-decoration: none; font-size:16px" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            {{ __('LOGOUT') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <p class="mt-4 text-muted">&copy; SIKPEG. v.1.0.1<br>
            <small>Developed by <a class="text-muted" href="https://id.linkedin.com/in/rsuregar/in-id">Rahmat Suregar</a></small>
        </p>

        </div>
    </div>
</div>

<script>
    $('#password').on('click', function(){
        Swal.fire({
            // icon: 'question',
            title: 'Ganti Password',
            html: `<input type="password" autocomplete="off" id="passwords" name="password" class="form-control mb-1" placeholder="Password baru min. 6 karakter">
                   <input type="password" autocomplete="off" id="confirm_password" name="_confirm_password" class="form-control" placeholder="Konfirmasi password baru">`,
            footer: '&copy; SIKPEG. v.1.0.1',
            showCancelButton: false,
        }).then( function(){
            $.ajax({
                type:'POST',
                url:"{{ route('ganti.password', auth()->id()) }}",
                data: {
                    'password': document.getElementById("passwords").value,
                    'password_confirmation': document.getElementById("confirm_password").value,
                    '_token': '{{ csrf_token() }}'
                    },
                cache: false,
                success: function(resp) {
                    console.log(resp)
                    Swal.fire(
                        "Sccess!",
                        "Your note has been saved!",
                        "success"
                    )
                },
                error: function(error){
                    console.log(error)
                    var er = error.responseJSON.errors.password;
                    if (er.length > 1) {
                        var info = er[0]+' - '+er[1]
                    } else {
                        var info = er[0]
                    }
                    console.log(info)
                    Swal.fire({
                        title: error.responseJSON.message,
                        text: `${info}`,
                        icon: 'error'
                    })
                }
            });
        }, function(dismiss) {
            if (dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Canceled Note",
                    "error"
                )
            }
        });
    });
</script>
