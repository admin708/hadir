
<div class="border border-primary rounded p-3 mb-3">
    <form wire:submit.prevent="update">
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
                    <label>Hari Libur | [angka1,angka2, dst]</label>
                    <input type="text" wire:model="hari_libur" class="form-control tagsinput @error('hari_libur') is-invalid @enderror input-sm" wire:model="hari_libur" data-role="tagsinput" placeholder="Hari Libur">
                    @error('hari_libur')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <button class="btn btn-warning btn-block">Update Hari Libur</button>
    </form>
</div>

@push('script-custom')
<script>
    $(document).ready(function() {
        $('.tagsinput').tagsinput({
            allowDuplicates: false,
        });
    });
</script>
@endpush

