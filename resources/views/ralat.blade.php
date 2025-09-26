@extends('layouts.app')
@push('style-custom')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Edit Jam Masuk</div>

                <div class="card-body">
                    @if (session('pesan'))
                        <div class="alert alert-{{ session('tipe') }}" role="alert">
                            {!! session('pesan') !!}
                        </div>
                    @endif

                    <form action="{{ route('hitung.post') }}" method="post">
                        @csrf
                        <div class="form-row">
                            @php
                            $unit = \App\Unitkerja::where('nip', auth()->user()->username)->pluck('id');
                                $get = \App\User::query();
                                $get->when(auth()->user()->role_id == 'admin' || auth()->user()->role_id == 'pimpinan_umum', function($q){
                                    return $q->where('status_pegawai', 1)->where('role_id', '<>', 'admin');
                                });
                                $get->when(auth()->user()->role_id == 'pimpinan', function($q) use($unit){
                                    return $q->whereIn('unitkerja_id', $unit)->where('status_pegawai', 1)->where('role_id', '<>', 'admin');
                                });
                                $get->get();
                            @endphp
                            {{-- {{ $unit }} --}}
                            <div class="form-group col-md-12">
                            <label for="inputEmail4">Cari Pegawai</label>
                            <select id="select2" name="user_id" class="form-control select2" data-placeholder="Cari Pegawai" required width="100%">
                                <option></option>
                                @foreach ($get->get() as $item)
                                    <option value="{{ $item->id }}" {{ old('user_id') == $item->id ? 'selected':'' }}>{{ $loop->iteration.' | '.$item->username.' - '.$item->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputAddress">Waktu Masuk</label>
                                <input type="datetime-local" class="form-control" id="inputAddress" value="{{ old('waktu') }}" name="waktu" required>
                                <input type="hidden" class="form-control" id="inputAddress" name="tipe" value="1">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="catatan">Alasan Pengubahan Waktu Masuk</label>
                                <textarea class="form-control" id="catatan" name="catatan" required>{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Ubah Jam Masuk</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-custom')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select a state",
                allowClear: true
            });
        });
    </script>
@endpush
