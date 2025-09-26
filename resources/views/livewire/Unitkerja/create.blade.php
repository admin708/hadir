<div class="border border-primary rounded p-3 mb-3">
    <form wire:submit.prevent="store">
        <div class="form-group">
            <label for="exampleInputPassword1">Nama Unit Kerja</label>
            <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror input-sm"
                placeholder="Nama Unit Kerja">
            @error('nama')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">NIP Pejabat Aktif <small> | Sebagai Pengesah</small></label>
            <input type="text" wire:model="nip" class="form-control @error('nip') is-invalid @enderror input-sm"
                placeholder="NIP Pejabat Aktif">
            @error('nip')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Nama Pejabat Aktif</label>
            <input type="text" wire:model="pejabat" class="form-control @error('pejabat') is-invalid @enderror input-sm"
                placeholder="Nama Pejabat Aktif">
            @error('pejabat')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="chekbox">Pilih Jadwal/Shift Kerja</label>
            @foreach ($jadwal as $item)
            <div class="custom-control custom-checkbox">
                <input type="checkbox" wire:model="jadwal_id"
                    class="custom-control-input @error('jadwal_id') is-invalid @enderror" value="{{ $item->id }}"
                    id="customCheck{{ $item->id }}">
                <label class="custom-control-label" for="customCheck{{ $item->id }}">{{ $item->nama }}</label>
            </div>
            @endforeach
            @error('jadwal_id')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button class="btn btn-primary btn-block">Tambah Unit Kerja</button>
    </form>
</div>
