@extends('layouts.app')
@push('style-custom')
{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.4/css/responsive.bootstrap4.min.css"> --}}
@endpush
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">Menu Data {{ request()->segment(1, 1) }}
                        <span class="float-right">
                            <a href="{{ route('pegawai.import') }}" class="btn btn-secondary ml-1">Download Template</a>
                            <button data-href="{{ route('pegawai.import') }}" class="btn btn-success ml-1 import">Import
                                Pegawai</button>
                        </span>
                    </h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @livewire('pegawai')


                    {{-- <table class="table table-bordered table-sm dt-responsive nowrap" width="100%" id="users-table">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th>NIP</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-custom')
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.4/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.4/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.4/js/responsive.bootstrap4.min.js"></script> --}}
{{-- <script>
    $(function() {
       var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route(request()->segment(1).'.ajax') }}",
columns: [
{data: 'DT_RowIndex',name: 'DT_RowIndex'},
{ data: 'username', name: 'nip' },
{ data: 'name', name: 'name' },
{ data: 'email', name: 'email' },
{ data: 'role_id', name: 'role' },
{ data: 'aksi', name: 'aksi' }
]
});
new $.fn.dataTable.FixedHeader( table );
});
</script> --}}

<script>
    $('.import').on("click", function () {
        var action = this.getAttribute('data-href');
        var _method = 'POST';
        var _token = $('meta[name="csrf-token"]').attr('content')
            Swal.queue([{
            title: "Import Pegawai",
            cancelButtonText: 'Batal',
            confirmButtonText: 'Upload',
            showCloseButton: true,
            allowOutsideClick: false,
            // text:`${action}/${_method}/${_token}`,
            html: `

                <input type="file" id="file" class="swal2-file" name="file" accept=".xlsx, .xls, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">`,
            type:'question',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const file = document.getElementById('file');
                let reader = new FileReader();
                reader.readAsDataURL(file.files[0]);
                const formData = new FormData();
                formData.append('file', file.files[0]);
                console.log(reader);
                return fetch(action, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": _token
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body:formData
                })
                .then(response => response.json())
                .then((data) => {
                    console.log(data)
                    if(data.status == 200){
                        swal.insertQueueStep({
                            type: 'success',
                            title: data.pesan,
                            // html: 'Keluar otomatis dalam <b></b> milliseconds. Atau klik <b>OK</b>',
                            timer: '1000',
                            onBeforeOpen: () => {
                                setTimeout(() =>{
                                    window.location.reload()
                                }, 1000)
                            },
                        })
                    }
                    else{
                        Swal.insertQueueStep({
                        type: 'error',
                        text: 'Kesalahan bisa diakibatkan sistem.',
                        title: data.pesan
                        })
                    }
                })
                // .then(data => Swal.insertQueueStep(data.ip))
                .catch((e) => {
                    Swal.insertQueueStep({
                    type: 'error',
                    text: 'Kesalahan bisa diakibatkan oleh jaringan atau sistem.' + e,
                    title: 'Whooups... Terjadi kesalahan. Coba lagi.'
                    })
                })
            }
            }])
    });
    // $('.iamport').click(function() {
    //     let action = this.getAttribute('data-href');
    //     let _method = 'POST';
    //     let _token = $('meta[name="csrf-token"]').attr('content')
    //     // console.log(media)
    //     Swal({
    //         cancelButtonText: 'Batal',
    //         showCancelButton: true,
    //         title: 'Import Pegawai',
    //         type:'question',
    //         confirmButtonText: 'Upload',
    //         html:
    //             `<form class="form" method="post" entype="multipart/form-data">
    //                 @csrf
    //             <label class="lead text-lowercase">Pilih file template</label>
    //             <input type="hidden" name="_method" value="${_method}">
    //             <input type="file" class="swal2-file" name="file" accept=".xlsx, .xls, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></form>`,
    //         onBeforeOpen: () => {
    //             // $(".swal2-file").change(function () {
    //             //     var reader = new FileReader();
    //             //     reader.readAsDataURL(this.files[0]);
    //             // });
    //             $('.form').on('submit', function(){
    //                 let reader = new FileReader();
    //                 reader.readAsDataURL($('.swal2-file').files[0]);
    //             })
    //         }
    //     }).then((file) => {
    //         if (file.value) {
    //             let form = $('.form')[0]
    //             let formData = new FormData(form)
    //             console.log(formData);
    //             $.ajax({
    //                 headers: { 'X-CSRF-TOKEN': _token },
    //                 method: 'POST',
    //                 url: action,
    //                 data: formData,
    //                 dataType: 'json',
    //                 processData: false,
    //                 contentType: false,
    //                 success: function (resp) {
    //                     console.log(resp)
    //                     if (resp.status == 200) {
    //                             swal.fire({
    //                             type: 'success',
    //                             title: 'Berhasil',
    //                             text: 'Berhasil mengimport peserta',
    //                             timer: '1000',
    //                             onBeforeOpen: () => {
    //                                 setTimeout(() =>{
    //                                     window.location.reload()
    //                                 }, 1000)
    //                             },
    //                         })
    //                     }else{
    //                         Swal({ type: 'error', title: 'Oops...', text: resp.pea.errorInfo[2]})
    //                     }
    //                 },
    //                 error: function(error) {
    //                     // console.log(error)
    //                     Swal({ type: 'error', title: 'Oops...', text: error.responseJSON})
    //                 }
    //             })
    //         }
    //     })
    // });
</script>

@endpush
