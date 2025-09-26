
<div class="border border-success rounded p-3 mb-3">
    <form wire:submit.prevent="import">
        <div class="form-group">
            <label for="exampleInputPassword1">Pilih template Excel</label>
            <input type="file" wire:model="file" class="form-control @error('file') is-invalid @enderror input-sm"  placeholder="Filih Import">
            @error('file')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button class="btn btn-success btn-block">Import Pegawai</button>
    </form>
</div>
