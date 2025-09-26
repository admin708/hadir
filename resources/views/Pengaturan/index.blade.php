@extends('layouts.app')
@push('style-custom')
{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.4/css/responsive.bootstrap4.min.css"> --}}
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">Menu {{ request()->segment(1, 1) }}
                    </h5>
                </div>

                <div class="card-body">
                    @livewire('pengaturans')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-custom')

@endpush
