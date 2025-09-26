<div>
    {{-- {{ $data }} --}}
    @empty($data)
    @include('livewire.Pengaturan.create')
    @else
    <div class="d-flex justify-content-end">
        <button type="button" wire:click="edit(1)" class="btn btn-warning">Change Edit</button>
    </div>
    <hr>
    @if ($showConfig)
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Nama Instansi</span>
            <span class="">{{ $data->nama_instansi }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Email Instansi</span>
            <span class="">{{ $data->email }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Domain Instansi (e.g mydomain.com / sub.mydomain.com)</span>
            <span class="">{{ $data->domain }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Rentang Jam Masuk</span>
            <span class="">{{ $data->range_masuk }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Rentang Jam Pulang</span>
            <span class="">{{ $data->range_pulang }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted">Serial Key</span>
            <span class="">{{ $data->keygen }}</span>
        </li>
    </ul>
    @endif
    @if ($updateMode)
    @include('livewire.Pengaturan.update')
    @endif
    @endempty

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
@endpush
