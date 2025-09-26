@extends('layouts.app')
@push('style-custom')
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/tagsinput.css') }}">
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
                    @livewire('jadwals')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


