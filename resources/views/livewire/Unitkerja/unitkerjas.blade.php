<div>
    <div class="row">
        <div class="col-md-6">
            <input wire:model="search" type="text" class="form-control form-control-sm mb-1" placeholder="Cari">
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            @if ($createMode || $updateMode)
                <button wire:click="openForm(false)" class="btn btn-sm btn-danger mb-1">Tutup Form</button>
            @else
                <button wire:click="openForm(true)" class="btn btn-sm btn-primary mb-1">Tambah Unit Kerja</button>
            @endif
        </div>
    </div>

    @if ($updateMode)
        @include('livewire.Unitkerja.update')
    @elseif($createMode)
        @include('livewire.Unitkerja.create')
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    {{-- <th class="text-center"width="3%">No</th> --}}
                    <th class="text-center">ID UNIT</th>
                    <th>Nama Unit Kerja</th>
                    <th>NIP Pejabat</th>
                    <th>Pejabat Pengesah</th>
                    <th class="text-center">Jumlah Pegawai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr class="{{ $confirming === $row->id ? 'table-danger text-danger' : '' }}">
                        {{-- <td class="text-center">{{$loop->index + 1}}</td> --}}
                        <td class="text-center">{{ $row->id }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->nip }}</td>
                        <td>{{ $row->pejabat }}</td>
                        <td class="text-center">{{ $row->pegawai_count }}</td>
                        <td>
                            <button wire:click="edit({{ $row->id }})"
                                class="btn btn-sm btn-outline-primary py-0">Edit</button>
                            @canany(['pimpinan', 'admin', 'pimpinan_umum'])
                                {{-- <button wire:click="destroy({{$row->id}})" class="btn btn-sm btn-outline-danger
                        py-0">Hapus</button> --}}
                                @if ($confirming === $row->id)
                                    <button wire:click="destroy({{ $row->id }})"
                                        class="btn btn-danger py-0">Yakin?</button>
                                @else
                                    <button wire:click="confirmDelete({{ $row->id }})"
                                        class="btn btn-sm btn-outline-danger py-0">Hapus</button>
                                @endif
                            @endcanany
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">{!! $cari
                            ? '<strong
                                                    class="text-danger">' .
                                $cari .
                                '</strong> tidak ditemukan.'
                            : 'Tidak ada data ditemukan.' !!}
                        </td>
                    </tr>
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
                showConfirmButton: false,
                showCloseButton: true,
                timer: param['type'] !== 'error' ? 1000 : '',
                text: param['message']
            })
            // console.log(param)
        })
    </script>
@endpush
