@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1 text-capitalize">{{ request()->segment(1, 1) }} Harian Bulan
                        {{ now()->translatedFormat('F Y') }}
                    </h5>
                </div>

                <div class="card-body">
                    @if (session('pesan'))
                    <div class="alert alert-{{ session('error') }} alert-dismissible fade show" role="alert">
                        <strong>{{ session('pesan') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="row d-flex justify-content-end mb-3">
                        <div class="col-md-12 text-center">
                            <div class="table-responsive">
                                <nav aria-label="Navigation">
                                    <ul class="pagination">
                                        @php
                                        foreach ($libur as $key) {
                                        $hari[$key-1] = 'libur';
                                        }

                                        @endphp
                                        @foreach ($hari as $key => $val)
                                        @if ($val != 'libur')
                                        <li class="page-item {{ $days == $val ? 'active':'' }}"><a
                                                class="page-link border border-primary"
                                                href="{{ $val == 'libur' ? '#':'?tanggal='.$val.'&jadwal='.$jdw.'&cari='.$cari }}">{!!
                                                $val !!}</a></li>
                                        @else
                                        <li class="page-item {{ $days == $key+1 ? 'active':'' }}"><button
                                                class="page-link border border-danger text-danger" disabled><i
                                                    class="bx bx-x"></i></button></li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-2 col-sm-12 text-right">
                            @php
                            $jadwal = \App\Jadwal::get();
                            @endphp
                            {{-- <nav class="nav nav-pills flex-column flex-sm-row">
                            @foreach ($jadwal as $item)
                            <a class="flex-sm-fill text-sm-center nav-link ml-1 mr-1" href="#">{{ $item->nama }}</a>
                            @endforeach
                            </nav> --}}
                            <select class="form-control" id="pilih_jadwal">
                                <option value="">Pilih Jadwal</option>
                                @foreach ($jadwal as $item)
                                <option value="{{ $item->id }}" {{ ($jdw ?? 1) == $item->id ? 'selected':'' }}>
                                    {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-4 col-sm-12 text-right border-left">
                            <form>
                                <input type="hidden" name="tanggal" value="{{ $days }}">
                                <input type="hidden" name="jadwal" value="{{ $jdw }}">
                                <input type="text" name="cari" value="{{ $cari ?? '' }}" class="form-control"
                                    placeholder="Cari Pegawai dan tekan Enter">
                                <button type="submit" class="d-none">Oke</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    {{-- <th rowspan="2" class="text-center align-middle">Status</th> --}}
                                    <th rowspan="2" class="text-center align-middle">NIP</th>
                                    <th rowspan="2" class="text-left align-middle">Nama</th>
                                    <th rowspan="2" class="text-center align-middle">Unit Kerja</th>
                                    <th rowspan="2" class="text-center align-middle">Status</th>
                                    <th colspan="4" class="text-center">Presensi {{ $tanggal->translatedFormat('l, d F Y') }}</th>
                                    <th rowspan="2" class="text-center align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Masuk <small class="text-danger">| Terlambat</small></th>
                                    <th class="text-center">Foto</th>
                                    {{-- <th class="text-center">Keterangan</th> --}}
                                    {{-- <th class="text-center">Istirahat <small class="text-danger">| Terlambat</small></th>
                                    <th class="text-center">Foto</th> --}}
                                    {{-- <th class="text-center">Keterangan</th> --}}
                                    <th class="text-center">Pulang</th>
                                    <th class="text-center">Foto</th>
                                    {{-- <th class="text-center">Keterangan</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($data as $item)
                                @php
                                $masuk = $item->presensi()->whereDate('waktu', $tanggal)->whereTipe(1)->first();
                                $istirahat = $item->presensi()->whereDate('waktu', $tanggal)->whereTipe(2)->first();
                                $pulang = $item->presensi()->whereDate('waktu', $tanggal)->whereTipe(3)->first();
                                $confirmed = $item->presensi()->whereDate('waktu',
                                $tanggal)->whereConfirmed(true)->count();

                                @endphp
                                
                                <tr>
                                    <td class="text-center">
                                        {{ ($data->currentpage()-1) * $data->perpage() + $loop->index + 1 }}</td>
                                    {{-- <td class="text-center"> <a href="#" class="btn btn-xs btn-{{ $confirmed > 2 ? 'success':'danger' }}">{{ $confirmed > 2 ? 'Accepted':'Rejected' }}</a>
                                    </td> --}}
                                    <td class="text-center">{{ $item->username }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ optional($item->unitkerja)->nama }}</td>
                                    <td class="text-center">
                                        @switch(isset($masuk['status_presensi_id']))
                                            @case(1)
                                                <span class="badge badge-success">Hadir</span>
                                                @break
                                            @case(3)
                                                <span class="badge badge-primary">Izin</span>
                                                @break
                                            @case(4)
                                                <span class="badge badge-warning">Sakit</span>
                                                @break
                                            @case(5)
                                                <span class="badge badge-secondary">Cuti</span>
                                                @break
                                            @case(2)
                                            <span class="badge badge-danger">Alpa</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">Belum Absen</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">{!! $masuk != null ? $masuk->auto_presensi ? '<span
                                            class="text-danger">x</span>':
                                        \Carbon\Carbon::parse($masuk->waktu)->format('H:i:s').' <small
                                            class="text-danger">| '.gmdate('H:i', $masuk->terlambat).'</small>':'<span
                                            class="text-danger">x</span>' !!}</td>
                                    <td class="text-center">
                                        @if ($masuk !=null)
                                        @if ($masuk->auto_presensi)
                                        <span class="text-danger">x</span>
                                        @else
                                        <a href="javascript:void(0)" class="viewImage" data-nama="{{ $item->name }}"
                                            data-telat="{{ gmdate('H:i:s',$masuk->terlambat) }}"
                                            data-title="Presensi Masuk"
                                            data-gps="{{ config('app.name') }}"
                                            {{-- data-gps="{{ $masuk->address }}" --}}
                                            data-text="Masuk pukul {{ \Carbon\Carbon::parse($masuk->waktu)->format('H:i:s') }}"
                                            data-image="{{ $masuk->foto !=null ? asset('storage/foto-kehadiran/'.$masuk->foto):asset('images/400.png') }}">Lihat</a>
                                        @endif
                                        @else
                                        <span class="text-danger">x</span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">{!! $istirahat != null ? \Carbon\Carbon::parse($istirahat->waktu)->format('H:i:s').' <small class="text-danger">| '.gmdate('H:i', $istirahat->terlambat).'</small>':'<span class="text-danger">x</span>' !!}</td>
                                    <td class="text-center">
                                        @if ($istirahat !=null)
                                        <a href="javascript:void(0)" class="viewImage" data-nama="{{ $item->name }}"
                                    data-telat="{{ gmdate('H:i:s',$istirahat->terlambat) }}" data-title="Presensi
                                    Istirahat" data-text="Absen Istirahat pukul
                                    {{ \Carbon\Carbon::parse($istirahat->waktu)->format('H:i:s') }}"
                                    data-image="{{ $istirahat->foto !=null ? asset('storage/foto-kehadiran/'.$istirahat->foto):asset('images/400.png') }}">Lihat</a>
                                    @else
                                    <span class="text-danger">x</span>
                                    @endif
                                    </td> --}}
                                    <td class="text-center">{!! $pulang != null ? $pulang->auto_presensi ? '<span
                                            class="text-danger">x</span>':
                                        \Carbon\Carbon::parse($pulang->waktu)->format('H:i:s'):'<span
                                            class="text-danger">x</span>' !!}</td>
                                    <td class="text-center">
                                        @if ($pulang !=null)
                                        @if ($pulang->auto_presensi)
                                         <span class="text-danger">x</span>
                                        @else
                                        <a href="javascript:void(0)" class="viewImage" data-nama="{{ $item->name }}"
                                            data-telat="{{ gmdate('H:i:s',$pulang->terlambat) }}"
                                            data-title="Presensi pulang"
                                            data-gps="{{ config('app.name') }}"
                                            {{-- data-gps="{{ $pulang->address }}" --}}
                                            data-text="Absen pulang pukul {{ \Carbon\Carbon::parse($pulang->waktu)->format('H:i:s') }}"
                                            data-image="{{ $pulang->foto !=null ? asset('storage/foto-kehadiran/'.$pulang->foto):asset('images/400.png') }}">Lihat</a>
                                        @endif
                                        @else
                                        <span class="text-danger">x</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($masuk != null && !$masuk->auto_presensi)
                                            <select name="gantiStatus" id="gantiStatus" class="form-control fomr-control-sm gantiStatus" {{ $masuk['auto_presensi'] ? '':'' }}>
                                                <option value="">Pilih Status</option>
                                                @foreach (\App\StatusPresensi::get() as $sp)
                                                <option value="{{ $masuk['user_id'].'_'.$masuk['waktu'].'_'.$sp->id }}" {{ $masuk['status_presensi_id'] == $sp->id ? 'selected disabled':'' }}>{{ $sp->kode.' - '.$sp->nama }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                        {{-- <span class="text-center text-muted">Tidak ada data absen</span> --}}
                                        {{-- <button class="btn btn-sm btn-primary" data-toggle="modal" data-target>Edit Status</button> --}}
                                        <a class="btn btn-sm btn-primary delete" href="#"  target-action="{{ route('presensi.manual', $item->id) }}">Edit Status</a>
                                        {{-- <select name="gantiStatus" id="gantiStatus" class="form-control fomr-control-sm gantiStatus" {{ $masuk['auto_presensi'] ? '':'' }}>
                                            <option value="">Pilih Status</option>
                                                @foreach (\App\StatusPresensi::get() as $sp)
                                                <option value="{{ $item->id.'_'.$masuk['waktu'].'_'.$sp->id }}" {{ $masuk['status_presensi_id'] == $sp->id ? 'selected disabled':'' }}>{{ $sp->kode.' - '.$sp->nama }}</option>
                                                @endforeach
                                            </select> --}}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Tidak ada data presensi ditemukan
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>

                        <span>Showing: {{ $data->total() }} orang</span>
                        <span class="float-right">
                            {{ $data->withQueryString()->links() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@component('Presensi._updatestatus')
    Ingin mengubah {{ ucwords(Request::segment(1)) }} tanggal {{ $tanggal->translatedFormat('d F Y') }}   ini?
@endcomponent
@endsection
@push('script-custom')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $('.viewImage').on('click', function(){
            console.log(this.getAttribute('data-nama'))
            Swal.fire({
                // title: this.getAttribute('data-title'),
                html: `<strong>${this.getAttribute('data-nama')}</strong> <br/>${this.getAttribute('data-text')} | Terlambat ${this.getAttribute('data-telat')}<br><small>Lokasi: ${this.getAttribute('data-gps')}</small>`,
                imageUrl: this.getAttribute('data-image'),
                imageWidth: 400,
                imageHeight: 400,
                showCloseButton: true,
                showConfirmButton: false
            })
        });

        $('#pilih_jadwal').on('change', function() {
            if (this.value !== '') {
                let url = "?tanggal={{ $days ?? '' }}&jadwal="+this.value+"&cari={{ $cari ?? '' }}"
                window.location.href = url;
            }
        });

        $('.gantiStatus').on('change', function() {
            var text = $(this).find("option:selected").text();
            if (this.value !== '' && confirm(`ingin mengubah status kehadiran pegawai ini menjadi ${text}?`)) {
                // alert(this.value)
                let url = "{{ route('presensi.ganti') }}?value="+this.value;
                window.location.href = url;
            }
        });
</script>
@endpush
