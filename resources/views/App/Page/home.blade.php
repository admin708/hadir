<div id="page-home" class="active">
    <h1 style="line-height: 30px" class="pb-3 text-{{ $color }} font-weight-bold text-capitalize">Selamat {{ $harini }}</h1>
    <span class="text-dark font-weight-bold mb-4" style="font-size:18px; line-height: 10px">{{ \Str::words(auth()->user()->name, 6) }}</span>
        <div class="text-danger border border-danger alert pt-2">
            <h4 class="font-weight-bold"><small class="text-muted" style="font-size:14px">Presensi {{ now()->translatedFormat('l') }}</small> <br>{{ now()->translatedFormat('d F Y') }} <span class="float-right text-dark" id="time">{{ now()->translatedFormat('H:i:s') }}</span></h4>
        </div>
     <div class="block mt-3">
        <ul class="list-unstyled">
            <li class="media border-bottom pb-3">
               <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_masuk }}"><img class="mr-3 rounded" src="{{$f_masuk }}" alt="{{ $p_masuk != null ? $p_masuk->foto:'Masuk' }}" height="70"></a>
              <div class="media-body">
                <h5 class="mt-0 mb-1">Presensi Masuk</h5>
                <strong>{!! $p_masuk !=null ? \Carbon\Carbon::parse($p_masuk->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">No data</span>' !!}</strong><br/>
                <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_masuk !=null ? $p_masuk->terlambat:0) }}</span>
              </div>
            </li>
            @if ($set->jam_istirahat != NULL)
            <li class="media border-bottom mt-3 pb-3">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_rehat }}"><img class="mr-3 rounded" src="{{$f_rehat }}" alt="{{ $p_rehat != null ? $p_rehat->foto:'Masuk' }}" height="70"></a>
                <div class="media-body">
                  <h5 class="mt-0 mb-1">Presensi Istirahat</h5>
                  <strong>{!! $p_rehat !=null ? \Carbon\Carbon::parse($p_rehat->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">No data</span>' !!}</strong><br/>
                  <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_rehat !=null ? $p_rehat->terlambat:0) }}</span>
                </div>
            </li>
            @endif
            <li class="media border-bottom mt-3 pb-3">
                <a href="javascript:void(0)" class="viewImage" data-image="{{ $f_pulang }}"><img class="mr-3 rounded" src="{{$f_pulang }}" alt="{{ $p_pulang != null ? $p_pulang->foto:'Masuk' }}" height="70"></a>
                <div class="media-body">
                  <h5 class="mt-0 mb-1">Presensi Pulang</h5>
                  <strong>{!! $p_pulang !=null ? \Carbon\Carbon::parse($p_pulang->waktu)->translatedFormat('H:i:s A'):'<span class="text-muted">No data</span>' !!}</strong><br/>
                  <span class="text-danger">Terlambat &#8594; {{ gmdate('H:i:s', $p_pulang !=null ? $p_pulang->terlambat:0) }}</span>
                </div>
            </li>
        </ul>
     </div>
</div>

<script>
var timeDisplay = document.getElementById("time");
function refreshTime() {
  var dateString = new Date().toLocaleString("id-ID", {timeZone: "Asia/Makassar"});
  var formattedString = dateString.split(" ")[1];
  var betulan = formattedString.split(".").join(':');
  timeDisplay.innerHTML = betulan;
  if(!isactive)
    $.get('{{ route('waktunnami') }}', (response)=>{
            console.log(response.status)
            if(!isactive && response.status) location.reload()
    })

}
setInterval(refreshTime, 1000);
        $('.viewImage').on('click', function(){
            // console.log(this.getAttribute('data-image'))
            Swal.fire({
                imageUrl: this.getAttribute('data-image'),
                imageWidth: 400,
                imageHeight: 400,
                showCloseButton: true,
                showConfirmButton: false
            })
        });
</script>
