@extends('layouts.app')
@push('style-custom')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Presensi Manual</div>

                <div class="card-body">
                    @if (session('pesan'))
                    <div class="alert alert-{{ session('tipe') }}" role="alert">
                        {!! session('pesan') !!}
                        @if (session('error'))
                            {!! session('error') !!}
                        @endif
                    </div>
                    @endif

                    <form action="{{ route('store.import') }}" method="post">
                        @csrf
                        <div class="form-row">
                            @php
                            $unit = \App\Unitkerja::where('nip', auth()->user()->username)->pluck('id');
                            $get = \App\User::query();
                            $get->when(auth()->user()->role_id == 'admin' || auth()->user()->role_id == 'pimpinan_umum',
                            function($q){
                            return $q->where('status_pegawai', 1)->where('role_id', '<>', 'admin');
                                });
                                $get->when(auth()->user()->role_id == 'pimpinan', function($q) use($unit){
                                return $q->whereIn('unitkerja_id', $unit)->where('status_pegawai', 1)->where('role_id',
                                '<>', 'admin');
                                    });
                                    $get->get();
                                    @endphp
                                    {{-- {{ $unit }} --}}
                                    <div class="form-group col-md-12">
                                        <label for="inputEmail4">Pilih Pegawai</label>
                                        <select id="select2" name="user_id[]" multiple="multiple" class="form-control select2 selectpicker" data-placeholder="Cari Pegawai" required width="100%" data-live-search="true" data-deselect-all-text="Hapus semua" data-select-all-text="Pilih semua" data-actions-box="true" title="Pilih pegawai..." data-selected-text-format="count > 3" >
                                            {{-- <option></option> --}}
                                            @foreach ($get->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('user_id') == $item->id ? 'selected':'' }}>
                                                {{ $loop->iteration.' | '.$item->username.' - '.$item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputAddress">Jam Masuk</label>
                                <input type="datetime-local" class="form-control" id="inputAddress"
                                    value="{{ old('masuk') }}" name="masuk" required>
                                <input type="hidden" class="form-control" id="inputAddress" name="tipemasuk" value="1">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jamPulang">Jam Pulang</label>
                                <input type="datetime-local" class="form-control" id="jamPulang" value="{{ old('pulang') }}" name="pulang" required>
                                <input type="hidden" class="form-control" id="jamPulang" name="tipepulang" value="3">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="statusAbsen">Status Kehadiran</label>
                                <select name="status_presensi_id" id="" class="form-control" required>
                                    <option value="">Pilih Status Kehadiran</option>
                                    @foreach (\App\StatusPresensi::get() as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('tipe') ? 'selected':'' }}>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="catatan">Alasan Presensi Manual Dilakukan</label>
                                <textarea class="form-control" id="catatan" name="catatan"
                                    required>{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Absensi manual</button>
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
            // $('.select2').select2({
            //     placeholder: "Select a state",
            //     allowClear: true
            // });
            // To style all selects
            $('select').selectpicker();
    });
</script>
@endpush
