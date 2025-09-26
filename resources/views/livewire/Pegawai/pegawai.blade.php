<div class="">
    <div class="row">
        <div class="col-md-4">
            <input wire:model="search" type="text" class="form-control mb-1" placeholder="Ketik minimal 4 karakter">
        </div>
        <div class="col-md-6">
            <form class="form-inline" action="{{ route('pegawai.export') }}" method="POST">
                @csrf
                @method('POST')
                <select name="unitkerja_id" class="form-control mb-1 mr-1" required>
                    <option value="">Pilih Data Pegawai</option>
                    <option value="all">All | Semua</option>
                    @forelse ($unit ?? [] as $item)
                        <option value="{{ $item->id }}">{{ 'ID. ' . $item->id . ' | ' . $item->nama }}</option>
                    @empty
                        <option disabled selected>Buat Unit Kerja terlebih dahulu</option>
                    @endforelse
                </select>
                <button type="submit" class="btn btn-primary mr-1 mb-1">Download Data Pegawai</button>

            </form>
        </div>
        <div class="col-md-2 d-flex justify-content-end">
            @if ($createMode || $updateMode)
                <button wire:click="openForm(false)" class="btn btn-danger mb-1">Tutup Form</button>
            @else
                <button wire:click="openForm(true)" class="btn btn-primary mb-1">Tambah Pegawai</button>
            @endif
        </div>
    </div>

    @if ($updateMode)
        @include('livewire.Pegawai.update')
    @elseif($createMode)
        @include('livewire.Pegawai.create')
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th>NIP</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Unit Kerja</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($data as $row)
                    <tr
                        class="{{ $confirming === $row->id ? 'table-danger text-danger' : '' }} {{ $row->status_pegawai === 2 ? 'table-warning' : '' }}">
                        <td class="text-center">{{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                        </td>
                        <td>{{ $row->username }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ optional($row->unitkerja)->nama }}</td>
                        <td>{{ optional($row->jadwal)->nama }}</td>
                        <td>
                            @if ($userId === $row->id)
                                <span wire:loading.remove wire:target="edit">
                                    <button wire:click="edit({{ $row->id }})"
                                        class="btn  btn-outline-primary py-0">Edit</button>
                                </span>
                                <div wire:loading wire:target="edit">
                                    <button class="btn btn-primary py-0 " type="button" disabled>
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            aria-hidden="true"></span>
                                        Edit
                                    </button>
                                </div>
                            @else
                                <button wire:click="edit({{ $row->id }})"
                                    class="btn  btn-outline-primary py-0">Edit</button>
                            @endif
                            @cannot('pegawai')
                                @if ($confirming === $row->id)
                                    <button wire:click="destroy({{ $row->id }})"
                                        class="btn btn-danger py-0 my-1">Yakin?</button>
                                @else
                                    <button wire:click="confirmDelete({{ $row->id }})"
                                        class="btn  btn-outline-danger py-0 my-1">Hapus</button>
                                @endif
                                {{-- <button wire:click="resetPassword({{$row->id}})" class="btn  btn-outline-success
                        py-0">Reset Password</button> --}}
                                <span wire:loading.remove wire:target="resetPassword">
                                    <button
                                        class="btn {{ $confirmReset === $row->id ? 'btn-success py-0' : 'btn-outline-success py-0' }}"
                                        wire:click="{{ $confirmReset === $row->id ? 'resetPassword(' . $row->id . ')' : 'confirmReset(' . $row->id . ')' }}">{{ $confirmReset === $row->id ? 'Yakin?' : 'Reset' }}</button>
                                </span>
                                @if ($confirmReset === $row->id)
                                    <div wire:loading wire:target="resetPassword">
                                        <button class="btn btn-success my-1" type="button" disabled>
                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                aria-hidden="true"></span>
                                            Resetting...
                                        </button>
                                    </div>
                                @endif
                                @if ($row->status_pegawai === 2)
                                    Sedang Cuti
                                @else
                                    <button wire:click="cuti({{ $row->id }})"
                                        class="btn btn-warning py-0 my-1">Cuti</button>
                                @endif
                            @endcannot
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">{!! $cari
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
    <span>Showing: {{ $data->total() }} orang</span>
    <span class="float-right">
        {{ $data->withQueryString()->links() }}
    </span>



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
        });

        window.livewire.on('reset', param => {
            // toastr[param['type']](param['message']);
            Swal.fire({
                type: param['type'],
                title: param['title'],
                showConfirmButton: false,
                showCloseButton: true,
                text: param['message'],
                allowEscapeKey: false,
                allowOutsideClick: false
            })
            // console.log(param)
        });
    </script>
@endpush
