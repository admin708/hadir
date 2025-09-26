@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">{{ request()->segment(1, 1) }} Presensi Bulan <strong
                            class="text-danger">{{ $bulan[$selected] }}</strong> Tahun {{ date('Y') }} <span
                            class="float-right">
                            <small>
                                <button class="btn btn-sm btn-primary mb-1" onclick="cetakfull()">Cetak Rekap
                                    Lengkap</button>
                                {{-- <button class="btn btn-sm btn-success mr-1 mb-1" onclick="cetaksummary()">Cetak Rekap Summary</button> --}}
                            </small>
                        </span>
                    </h5>
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-lg-8 col-md-12 col sm-12">
                            <div class="table-responsive">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link bg-warning text-dark"
                                                href="{{ route('rekap.index') }}">Reset</a></li>
                                        @foreach ($bulan as $key => $val)
                                        <li class="page-item {{ $selected == $key ? 'active':'' }}"><a class="page-link"
                                                href="{{ $val == 'libur' ? '#':'?bulan='.$key.'&jadwal='.$jdw.'&cari='.$cari }}">{!!
                                                $val !!}</a></li>
                                        @endforeach
                                        <li class="page-item"><a class="page-link bg-warning text-dark"
                                                href="{{ route('rekap.index') }}">Reset</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-2 col-sm-12">
                            @php
                            $jadwal = \App\Jadwal::get();
                            @endphp
                            <select class="form-control" id="pilih_jadwal">
                                <option value="">Pilih Jadwal</option>
                                @foreach ($jadwal as $item)
                                <option value="{{ $item->id }}" {{ ($jdw ?? 1) == $item->id ? 'selected':'' }}>
                                    {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-2 col-sm-12 text-right">
                            <form>
                                <input type="hidden" name="tanggal" value="{{ $selected }}">
                                <input type="hidden" name="jadwal" value="{{ $jdw }}">
                                <input type="text" name="cari" value="{{ $cari ?? '' }}" class="form-control"
                                    placeholder="Cari Pegawai dan tekan ENTER">
                                <button type="submit" class="d-none">Oke</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table-sm table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" width="3%">
                                        #</th>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" colspan="2"
                                        width="20%">NIP & Nama</th>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" width="5%">
                                        Presensi</th>
                                    <th class="text-center align-middle" style="font-size: 10pt"
                                        colspan="{{ count($hari) }}">Bulan {{$bulan[$selected].' '.date('Y') }}</th>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" width="2%">
                                        <div style="
                                        transform: rotate(90deg)">Total</div>
                                    </th>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" width="3%">
                                        <div style="
                                            transform: rotate(90deg)">Telat</div>
                                    </th>
                                    <th class="text-center align-middle" style="font-size: 10pt" rowspan="2" width="3%">
                                        <div style="
                                            transform: rotate(0deg)">%</div>
                                    </th>
                                </tr>
                                <tr>
                                    @foreach ($hari as $item)
                                    <th class="text-center align-middle" style="font-size: 10pt">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data ?? [] as $item)
                                <tr>
                                    <td class="align-baseline text-center" rowspan="2" style="font-size: 10pt">
                                        {{ ($data ->currentpage()-1) * $data ->perpage() + $loop->index + 1 }}<br />
                                        <a href="{{ route('rekap.show', $item->username) }}?action=detail&bulan={{ $selected }}&jadwal={{ $jdw }}"
                                            class="btn btn-xs btn-primary mr-1 ml-1 mb-1">Detail</a>
                                        {{-- <a href="{{ route('rekap.show', $item->username) }}?action=cetak&bulan={{ $selected }}&jadwal={{ $jdw }}"
                                        class="btn btn-xs btn-success mb-1 mr-1 ml-1">Cetak</a> --}}
                                    </td>
                                    <td class="align-baseline text-left" rowspan="2" colspan="2"
                                        style="font-size: 10pt">{{ $item->username }} <br> {{ $item->name }}</td>
                                    <td class="table-info font-weight-bold" style="font-size: 10pt">Masuk</td>
                                    @php

                                    foreach ($libur as $key) {
                                    $days[$key-1] = 'libur';
                                    }

                                    $efektif = $hari->count()-$libur->count();

                                    $masuk = $item->presensi()->where('user_id', $item->id)->where('tipe',
                                    1)->whereStatusPresensiId(1)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->get();

                                    $rehat = $item->presensi()->where('user_id', $item->id)->where('tipe',
                                    2)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->get();

                                    $pulang = $item->presensi()->where('user_id', $item->id)->where('tipe',
                                    3)->whereStatusPresensiId(1)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->get();

                                    // foreach ($masuk as $key => $value) {
                                    // $days[\Carbon\Carbon::parse($value->waktu)->format('d')-1] =
                                    // \Carbon\Carbon::parse($value->waktu)->format('H:i');
                                    // }

                                    $fill = [];
                                    foreach ($masuk as $f){
                                    $fill[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                                    }

                                    $fillR = [];
                                    foreach ($rehat as $f){
                                    $fillR[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                                    }

                                    $fillP = [];
                                    foreach ($pulang as $f){
                                    $fillP[] = \Carbon\Carbon::parse($f->waktu)->format('d');
                                    }
                                    @endphp
                                    @foreach ($days as $key => $vmasuk)
                                    @php
                                    $checked = "";
                                    if(in_array($key+1, $fill)){
                                    $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe',
                                    1)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu',
                                    \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first();
                                    $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                                    }else{
                                    $checked = '<i class="align-middle bx bx-x text-danger"></i>';
                                    }
                                    @endphp
                                    <td style="font-size: 10pt" width="2%"
                                        class="text-center table-info {{ $vmasuk == 'libur' ? 'table-danger text-danger':'' }} align-middle">
                                        {!! $vmasuk == 'libur' ? '<div style="font-size:10px;transform: rotate(90deg)">
                                            Libur</div>':'<small class="font-weight-bold">'.$checked.'</small>' !!}</td>
                                    @endforeach
                                    <td style="font-size: 10pt" class="text-center table-info font-weight-bold">
                                        {{ $masuk->count() }}</td>
                                    <td class="text-center table-info font-weight-bold" style="font-size: 12px">
                                        {{-- {{ gmdate('H:i:s',$masuk->sum('terlambat')) }} --}}

                                        {{ \App\Jadwal::getLate($item->id, $selected)->telat_totalv2 }}
                                    </td>
                                    <td style="font-size: 10pt" class="text-center table-info font-weight-bold">
                                        {{ round($masuk->count()/$efektif*100, 2) }}</td>
                                </tr>

                                {{-- <tr class="">
                                    <td style="font-size: 10pt" class="table-warning font-weight-bold">Istirahat</td>
                                    @foreach ($days as $key => $vmasuk)
                                    @php
                                        $checked = "";
                                        if(in_array($key+1, $fillR)){
                                            $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe', 2)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu', \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first();
                                            $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                                        }else{
                                            $checked = '<i class="align-middle bx bx-x text-danger"></i>';
                                        }
                                    @endphp
                                    <td style="font-size: 10pt" width="2%" class="text-center table-warning {{ $vmasuk == 'libur' ? 'table-danger text-danger':'' }}
                                align-middle">{!! $vmasuk == 'libur' ? '<div
                                    style="font-size:10px;transform: rotate(90deg)">Libur</div>':'<small
                                    class="font-weight-bold">'.$checked.'</small>' !!}</td>
                                @endforeach
                                <td style="font-size: 10pt" class="table-warning text-center font-weight-bold">
                                    {{ $rehat->count() }}</td>
                                <td class="table-warning text-center font-weight-bold" style="font-size: 12px">
                                    {{ gmdate('H:i:s',$rehat->sum('terlambat')) }}</td>
                                <td style="font-size: 10pt" class="table-warning text-center font-weight-bold">
                                    {{ round($rehat->count()/$efektif*100, 2) }}</td>
                                </tr> --}}
                                <tr class="">

                                    <td class="table-secondary font-weight-bold" style="font-size: 10pt">Pulang</td>
                                    @foreach ($days as $key => $vmasuk)
                                    @php
                                    $checked = "";
                                    if(in_array($key+1, $fillP)){
                                    $dapat = $item->presensi()->where('user_id', $item->id)->where('tipe',
                                    3)->whereMonth('waktu', $selected)->whereYear('waktu', $tahun)->whereDate('waktu',
                                    \Carbon\CarbonImmutable::create($tahun, $selected, $key+1))->first();
                                    $checked = \Carbon\Carbon::parse($dapat->waktu)->format('H:i');
                                    }else{
                                    $checked = '<i class="align-middle bx bx-x text-danger"></i>';
                                    }
                                    @endphp
                                    <td style="font-size: 10pt" width="2%"
                                        class="text-center {{ $vmasuk == 'libur' ? 'table-danger text-danger':'table-secondary' }} align-middle">
                                        {!! $vmasuk == 'libur' ? '<div style="font-size:10px;transform: rotate(90deg)">
                                            Libur</div>':'<small class="font-weight-bold">'.$checked.'</small>' !!}</td>
                                    @endforeach
                                    <td style="font-size: 10pt" class="table-secondary text-center font-weight-bold">
                                        {{ $pulang->count() }}</td>
                                    <td class="table-secondary text-center font-weight-bold" style="font-size: 12px">
                                    </td>
                                    {{-- <td class="table-secondary text-center font-weight-bold" style="font-size: 12px">{{ gmdate('H:i:s',$pulang->sum('terlambat')) }}
                                    </td> --}}
                                    <td style="font-size: 10pt" class="table-secondary text-center font-weight-bold">
                                        {{ round($pulang->count()/$efektif*100, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ count($hari)+7 }}" class="text-center table-secondary text-muted">No
                                        data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex mt-2 justify-content-end">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<iframe src="" id="target" name='target' style="display:none"></iframe>
@endsection
@push('script-custom')
<script>
    function cetakfull(){
            document.title = "Rekap Presensi {{ $jadwalKu->jadwal_name }} {{ $bulan[$selected].' '.date('Y')}}";
            // var x = document.documentURI = "{{ route('rekap.cetak', 'full') }}?bulan={{ $selected }}";
            var target = document.getElementById('target');
            target.src = "{{ route('rekap.cetak', 'full') }}?bulan={{ $selected }}&jadwal={{ $jdw }}";
        }

        function cetaksummary(){
            alert('Belum tersedia')
            // var target = document.getElementById('target');
            // target.src = "{{ route('rekap.cetak', 'summary') }}?bulan={{ $selected }}";
        }

        $('#pilih_jadwal').on('change', function() {
            if (this.value !== '') {
                let url = "?bulan={{ $selected ?? '' }}&jadwal="+this.value+"&cari={{ $cari ?? '' }}"
                window.location.href = url;
            }
        });
</script>
@endpush
