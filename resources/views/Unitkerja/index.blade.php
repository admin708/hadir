@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">Menu Data {{ request()->segment(1, 1) }}
                    </h5>
                </div>

                <div class="card-body">
                    @livewire('unitkerjas')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
