{{-- <div>
    <input type="hidden" wire:model="userId">
    <div class="form-group">
        <label for="exampleInputPassword1">NIP/Username</label>
        <input type="text" wire:model="username" class="form-control input-sm"  placeholder="Username">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Nama Lengkap</label>
        <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
    </div>
    <div class="form-group">
        <label>Enter Email</label>
        <input type="email" class="form-control input-sm" placeholder="Enter email" wire:model="email">
    </div>
    <button wire:click="update()" class="btn btn-warning">Update</button>
</div> --}}

<div class="border border-warning rounded p-3 mb-3">
    <form wire:submit.prevent="update">
        <input type="hidden" wire:model="userId">
        <div class="form-group">
            <label for="exampleInputPassword1">NIP/NIK/No.Identitas</label>
            <input type="text" wire:model="username" class="form-control input-sm"  placeholder="Username">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Nama Lengkap</label>
            <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
        </div>
        <div class="form-group">
            <label for="unit">Unit Kerja</label>
            <select wire:model="unitkerja_id" class="form-control @error('unitkerja_id') is-invalid @enderror input-sm" id="unit">
                <option value="">Pilih Unit Kerja</option>
                @forelse ($unit ?? [] as $item)
                    <option value="{{ $item->id }}">{{ 'ID. '.$item->id.' | '.$item->nama }}</option>
                @empty
                    <option disabled selected>Buat Unit Kerja terlebih dahulu</option>
                @endforelse
            </select>
            @error('unitkerja_id')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="asal">Pilih Jadwal</label>
            <select wire:model="jadwal_id" class="form-control @error('jadwal_id') is-invalid @enderror input-sm" id="asal">
                <option value="">Pilih Jadwal</option>
                @forelse ($jadwal ?? [] as $item)
                    <option value="{{ $item->id }}" {{ $jadwal_id == $item->id ? 'selected':'' }}>{{ 'ID. '.$item->id.' | '.$item->nama }}</option>
                @empty
                    <option disabled selected>Buat Jadwal Kerja terlebih dahulu</option>
                @endforelse
            </select>
            @error('jadwal_id')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label>Enter Email</label>
            <input type="email" class="form-control input-sm" placeholder="Enter email" wire:model="email">
        </div>
        <span wire:loading.remove wire:target="update">
            <button class="btn btn-warning btn-block">Update Pegawai</button>
        </span>
        <div wire:loading wire:target="update" class="text-center">
            <button class="btn btn-warning btn-block" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Updating process...
            </button>
        </div>
    </form>
</div>
