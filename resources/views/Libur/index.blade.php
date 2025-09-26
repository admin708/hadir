@extends('layouts.app')
@push('style-custom')
{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/tagsinput.css') }}">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.4/css/responsive.bootstrap4.min.css"> --}}
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">Menu Hari {{ request()->segment(1, 1) }}
                            {{-- <span class="float-right">
                                <a href="{{ route('pegawai.import') }}" class="btn btn-sm btn-secondary ml-1">Download Template</a>
                                <button data-href="{{ route('pegawai.import') }}" class="btn btn-sm btn-success ml-1 import">Import Pegawai</button>
                            </span> --}}
                    </h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @livewire('liburs')

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-custom')
{{-- <script src="{{ asset('js/tagsinput.js') }}"></script>
<script>
    $('input').tagsinput({
            allowDuplicates: false,
    });
</script> --}}
@endpush

