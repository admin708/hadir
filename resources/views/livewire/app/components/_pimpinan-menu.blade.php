<a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('presensi')" class="item {{ $page == 'presensi' ? 'active':'' }}">
    <div class="col" style="font-size: 12px">
        <ion-icon wire:ignore name="home-outline"></ion-icon>
        <strong>Home</strong>
    </div>
</a>
<a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('rekap')" class="item {{ $page == 'rekap' ? 'active':'' }}">
    <div class="col" style="font-size: 12px">
        <ion-icon wire:ignore name="document-text-outline"></ion-icon>
        <strong>Rekap</strong>
    </div>
</a>

@if ($pulang_cepat)
<a id="camera" href="javascript:void(0);" {{ $page == 'home' ? "onClick=pulang_cepat_snapshot(true)":"wire:click=showPage('home')" }}
    class="item {{ $page == 'home' ? 'active':'' }}">
    <div class="col">
        <div class="action-button large {{ $page == 'home' ? '':'bg-secondary' }}">
            <img src="{{ asset('images/camera.png') }}" height="32" alt="">
        </div>
    </div>
</a>
@else
<a id="camera" href="javascript:void(0);" {{ $page == 'home' ? "onClick=preview_snapshot()":"wire:click=showPage('home')" }}
    class="item {{ $page == 'home' ? 'active':'' }}">
    <div class="col">
        <div class="action-button large {{ $page == 'home' ? '':'bg-secondary' }}">
            <img src="{{ asset('images/camera.png') }}" height="32" alt="">
        </div>
    </div>
</a>
@endif

<a href="javascript:void(0);" onclick="Webcam.reset()" wire:click="showPage('profil')" class="item {{ $page == 'profil' ? 'active':'' }}">
    <div class="col" style="font-size: 12px">
        <ion-icon wire:ignore name="person-outline"></ion-icon>
        <strong>Profil</strong>
    </div>
</a>

<a href="{{ route('presensi.index') }}" class="item">
    <div class="col" style="font-size: 12px">
        <ion-icon wire:ignore name="apps-outline"></ion-icon>
        <strong>Admin</strong>
    </div>
</a>
