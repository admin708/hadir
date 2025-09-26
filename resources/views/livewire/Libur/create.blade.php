<div class="border border-primary rounded p-3 mb-3">
    <form wire:submit.prevent="store">
        <div class="row">
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Bulan</label>
                    <input type="month" class="form-control @error('bulan_tahun') is-invalid @enderror input-sm" placeholder="Bulan" wire:model="bulan_tahun">
                    @error('bulan_tahun')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Hari Libur | Pemisah dengan (,). Contoh: 1,2,3 => Tanggal 1,2 dan 3</label>
                    <input type="text" wire:model="hari_libur" class="form-control tagsinput @error('hari_libur') is-invalid @enderror input-sm" wire:model="hari_libur"  placeholder="Hari Libur">
                    @error('hari_libur')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <button class="btn btn-primary btn-block">Tambah Hari Libur</button>
    </form>
</div>


