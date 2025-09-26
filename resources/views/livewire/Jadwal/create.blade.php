<div class="border border-primary rounded p-3 mb-3">
    <form wire:submit.prevent="store">
        <div class="row">
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror input-sm" placeholder="Nama Jadwal" wire:model="nama">
                    @error('nama')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Lama Hari Kerja/pekan </label>
                    <input type="number" minlength="5" maxlength="7" wire:model="lama_hari" class="form-control @error('lama_hari') is-invalid @enderror input-sm" wire:model="lama_hari" placeholder="Lama Hari kerja dalam sepekan">
                    @error('lama_hari')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Toleransi Keterlambatan (dalam menit)</label>
                    <input type="number" wire:model="toleransi_terlambat" class="form-control  @error('toleransi_terlambat') is-invalid @enderror input-sm" wire:model="toleransi_terlambat" placeholder="Toleransi Keterlambatan">
                    @error('toleransi_terlambat')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Jam Masuk</label>
                    <input type="time" wire:model="clock_in" class="form-control  @error('clock_in') is-invalid @enderror input-sm" wire:model="clock_in" placeholder="Jam Masuk">
                    @error('clock_in')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Jam Pulang</label>
                    <input type="time" wire:model="clock_out" class="form-control  @error('clock_out') is-invalid @enderror input-sm" wire:model="clock_out" placeholder="Jam Pulang">
                    @error('clock_out')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <button class="btn btn-primary btn-block">Tambah Jadwal</button>
    </form>
</div>

