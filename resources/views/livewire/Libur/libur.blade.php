
<div class="">
    <div class="row">
        {{ $data }}
        <div class="col-md-6">
            {{-- <input wire:model="search" type="text" class="form-control form-control-sm mb-1" placeholder="Cari"> --}}
            @php
                $bulan = array("1" => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            @endphp
            <select wire:model="search" class="form-control form-control-sm mb-1">
                <option value="" {{ $cari == null ? 'selected':'' }}>Pilih Bulan</option>
                @foreach ($bulan as $key => $val)
                    <option value="{{ $key }}" {{ $key == $cari ? 'selected':'' }}>{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            @if ($createMode || $updateMode)
                <button wire:click="openForm(false)" class="btn btn-sm btn-danger mb-1">Tutup Form</button>
            @else
                <button wire:click="openForm(true)" class="btn btn-sm btn-primary mb-1">Tambah Hari Libur</button>
            @endif
        </div>
    </div>

    @if($updateMode)
        @include('livewire.Libur.update')
    @elseif($createMode)
        @include('livewire.Libur.create')
    @endif


        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Hari Libur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data as $row)
                    <tr class="{{ $confirming===$row->id ? 'table-danger text-danger':'' }}">
                        <td>{{$loop->index + 1}}</td>
                        <td>{{\Carbon\Carbon::parse($row->bulan_tahun)->translatedFormat('F')}}</td>
                        <td>{{\Carbon\Carbon::parse($row->bulan_tahun)->translatedFormat('Y')}}</td>
                        <td>{{$row->hari_libur}}</td>
                        <td>
                            <button wire:click="edit({{$row->id}})" class="btn btn-sm btn-outline-primary py-0">Edit</button>
                            @canany(['pimpinan', 'admin'])
                            {{-- <button wire:click="destroy({{$row->id}})" class="btn btn-sm btn-outline-danger py-0">Hapus</button> --}}
                            @if($confirming===$row->id)
                            <button wire:click="destroy({{ $row->id }})" class="btn btn-danger py-0">Yakin?</button>
                             @else
                            <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-sm btn-outline-danger py-0">Hapus</button>
                            @endif
                            @endcanany
                        </td>
                    </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">{!! $cari ? 'Data bulan <strong class="text-danger">'.$bulan[$cari].'</strong> tidak ditemukan.' :'Tidak ada data ditemukan.' !!}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $data->links() }}
        </div>
</div>
@push('script-custom')
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        window.livewire.on('alert', param => {
            // toastr[param['type']](param['message']);
            Swal.fire({
                type: param['type'],
                title: param['title'],
                showConfirmButton:false,
                showCloseButton:true,
                timer:param['type'] !== 'error' ? 1000:'',
                text: param['message']
            })
            // console.log(param)
        })
    </script>
    <script>
        $(document).ready(function() {
            $('.tagsinput').tagsinput({
                allowDuplicates: false,
            });
        });
    </script>
@endpush
