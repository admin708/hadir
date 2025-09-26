
<div id="page-faq" class="inactive">
    <div class="text-danger border border-danger alert pt-2">
        <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Frequently</small> <br>Question & Answer</h4>
    </div>
    <div class="block mt-3">
        <div id="accordion">
            @forelse ($faq as $item)
            <div class="card">
                <div class="card-header bg-white" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="padding: .2rem 1rem" id="headingOne">
                  <h6 class="my-2">
                    <a class="align-middle" href="javascript:void(0)" style="text-decoration: none" >
                      {{ $item->judul }}
                    </a>
                  </h6>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body" style="font-size: 10pt">
                    {{ $item->deskripsi }}
                  </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">Tidak ada FAQ tersedia</p>
            @endforelse
          </div>

    </div>
</div>

