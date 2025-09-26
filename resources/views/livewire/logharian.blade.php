<div>
    <div class="text-center">
        <div class="table-responsive">
            <nav aria-label="Navigation">
                <ul class="pagination">
                    @php
                        foreach ($libur as $key) {
                            $hari[$key-1] = 'libur';
                        }
                    @endphp
                  @foreach ($hari as $key => $val)
                  @if ($val != 'libur')
                  <li class="page-item {{ $page  == $val ? 'active':'' }}">
                    <button class="page-link border border-primary" wire:model="search">{!! $val  !!} </button>
                 </li>
                  @else
                  <li class="page-item {{ $page  == $key+1 ? 'active':'' }}"><button class="page-link border border-danger text-danger" disabled><i class="bx bx-x"></i></button></li>
                  @endif
                  @endforeach
                </ul>
            </nav>
        </div>
    </div>

</div>
