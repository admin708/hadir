<div class="border border-warning rounded p-3 mb-3">
    <form wire:submit.prevent="update">
        {{-- <input type="text" wire:model="theId"> --}}
        <div class="form-group">
            <label for="exampleInputPassword1">Nama Instansi</label>
            <input type="text" wire:model="nama_instansi" class="form-control @error('nama_instansi') is-invalid @enderror input-sm"  placeholder="Nama Instansi" value="{{ $data->nama_instansi }}">
            @error('nama_instansi')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Situs Domain</label>
            <input type="text" wire:model="domain" class="form-control @error('domain') is-invalid @enderror input-sm"  placeholder="Situs Domain">
            @error('domain')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Activation Key</label>
            <input type="text" wire:model="keygen" class="form-control @error('keygen') is-invalid @enderror input-sm"  placeholder="Activation Key">
            @error('keygen')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror input-sm" placeholder="Email" wire:model="email">
            @error('email')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        {{-- <div class="form-group">
            <label>Toleransi Keterlambatan</label>
            <input type="number" class="form-control @error('toleransi_terlambat') is-invalid @enderror input-sm" placeholder="Default: 0" wire:model="toleransi_terlambat" required>
            @error('toleransi_terlambat')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div> --}}
        <div class="row">
            {{-- <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Jam Masuk</label>
                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror input-sm" placeholder="Jam Masuk" wire:model="jam_masuk">
                    @error('jam_masuk')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div> --}}
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Rentang Jam Masuk [sebelum,sesudah]</label>
                    <input type="text" class="form-control @error('range_masuk') is-invalid @enderror input-sm" placeholder="0,1" wire:model="range_masuk">
                    @error('range_masuk')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Jam Istirahat</label>
                    <input type="time" class="form-control @error('jam_istirahat') is-invalid @enderror input-sm" placeholder="Jam Masuk" wire:model="jam_istirahat">
                    @error('jam_istirahat')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Rentang Jam Istirahat [sebelum,sesudah]</label>
                    <input type="text" class="form-control @error('range_istirahat') is-invalid @enderror input-sm" placeholder="0,1" wire:model="range_istirahat">
                    @error('range_istirahat')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Jam Pulang</label>
                    <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror input-sm" placeholder="Jam Pulang" wire:model="jam_pulang">
                    @error('jam_pulang')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div> --}}
            <div class="col-sm-3 col-md-6">
                <div class="form-group">
                    <label>Rentang Jam Pulang [sebelum,sesudah]</label>
                    <input type="text" class="form-control @error('range_pulang') is-invalid @enderror input-sm" placeholder="0,1" wire:model="range_pulang">
                    @error('range_pulang')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <button class="btn btn-warning btn-block">Update Pengaturan</button>
    </form>
</div>
