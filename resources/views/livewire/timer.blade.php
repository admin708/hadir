<div wire:poll.5000ms="timer">
<div class="section mb-2" style="margin-top: -30px">

    <div class="card">
        <div class="card-body p-1 pl-2 pr-2">
            <div>
                <h6 class="card-subtitle mt-1" style="line-height: 10px">Presensi hari ini</h6>
                <h5 style="line-height: 15px" class="card-title">
                    <span style="font-size: 18px" class="font-weight-bold">{{ now()->translatedFormat('l, d M Y') }}</span>
                    <span class="float-right">
                        @livewire('clock')
                        {{-- {{ now()->translatedFormat('H:i:s') }} --}}
                    </span>
                </h5>
            </div>
        </div>
    </div>
</div>
</div>
