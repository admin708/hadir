{{-- <div class="section full no-border" style="margin-top:-20px;">
    <div class="wide-block no-border mt-2 pt-5 pb-5 bg-danger text-center">
        <div wire:loading wire:target="showPage">
            <div class="text-center spinner-grow text-white" role="status"></div>
        </div>
    </div>
</div> --}}
<div class="section" style="margin-top: -30px">
    <div class="card p-2">
            <div class="profile-head">
                <div class="avatar">
                    <img src="https://ui-avatars.com/api/?background=1E74FD&color=fff&name={{ auth()->user()->name }}" alt="avatar"
                        class="imaged w64 rounded">
                </div>
                <div class="in">
                    <h3 class="name">{{ auth()->user()->name }}</h3>
                    <h5 class="subtext">{{ ucwords(Str::of(auth()->user()->role_id)->replace('_', ' ')) }} ({{ auth()->user()->unitkerja->nama }})</h5>
                </div>
            </div>
    </div>
</div>

<div class="section mt-2">
    <div class="card">
    <div class="wide-block transparent p-0">
        <ul class="listview image-listview text flush transparent pt-1">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Email
                        </div>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Username
                        </div>
                        <span>{{ auth()->user()->username }}</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Dark Mode
                            <footer>Turn into dark/lite mode</footer>
                        </div>
                        <div class="custom-control custom-switch">
                            <input wire:ignore type="checkbox" class="custom-control-input dark-mode-switch custom-control-sm"
                                id="darkmodeswitch">
                            <label class="custom-control-label" for="darkmodeswitch"></label>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Ganti password
                        </div>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#DialogForm"><span class="badge badge-danger">Change</span></a>
                    </div>
                </div>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();localStorage.setItem('currentPage', 'home')" class="item">
                    <div class="in">
                        <div>Logout</div>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    </div>
</div>

@include('livewire.app.footer')

<div wire:ignore class="modal fade dialogbox" id="DialogForm" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
            </div>
            <form wire:submit.prevent="changePassword">
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="password">Password</label>
                            <input id="password" wire:model="password" type="password" placeholder="Minimal 6 karakter" class="form-control" required autocomplete="new-password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-text-danger">UPDATE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



