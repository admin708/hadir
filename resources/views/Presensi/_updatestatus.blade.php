<div class="modal" id="modalConfirmation" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ $slot }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('POST')

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tanggal">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="tanggal" placeholder="Tanggal Masuk" name="waktu" required>
                        <input type="hidden" class="form-control" id="masuk" value="1" name="tipe[]">
                        <input type="hidden" class="form-control" id="pulang" value="3" name="tipe[]">
                    </div>
                    <div class="form-group col-md-6">
                        {{-- <label for="masuk">Waktu Pulang</label>
                        <input type="datetime-local" class="form-control" id="masuk" placeholder="Tanggal Masuk" name="waktu[]" req>
                        <input type="hidden" class="form-control" id="masuk" value="3" name="tipe[]"> --}}
                        <label for="inputState">Status Kehadiran</label>
                    <select id="inputState" name="status" class="form-control" required>
                        <option selected>Pilih Status</option>
                        @foreach (\App\StatusPresensi::get() as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach

                        {{-- <option value="2">Alpa</option>
                        <option value="3">Sakit</option>
                        <option value="4">Izin</option>
                        <option value="5">Cuti</option> --}}
                    </select>
                    </div>
                </div>
                {{-- <div class="form-row">
                    <div class="form-group col-md-12">
                    <label for="inputState">Status Kehadiran</label>
                    <select id="inputState" class="form-control" required>
                        <option selected>Pilih Status</option>
                        <option value="3">Sakit</option>
                        <option value="4">Izin</option>
                        <option value="5">Cuti</option>
                    </select>
                    </div>
                </div> --}}

                <div class="form-row">
                    <div class="form-group col-md-12">
                    <label for="catat">Catatan</label>
                    <textarea name="catatan"  class="form-control" rows="2"></textarea>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan </button>
          <button type="submit" class="btn btn-primary">Simpan data</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal" id="theModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">

      </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.delete').click(function(){
            var action = this.getAttribute('target-action');
            console.log(action)
            $("#deleteForm").attr('action', action);
            $('#modalConfirmation').modal('toggle');
        });


        $('.li-modal').on('click', function(e){
    // alert('halo');
        $('#theModal').modal('show').find('.modal-content').html('<div class="modal-body">Loading...</div>');

        e.preventDefault();
        $('#theModal').modal('show').find('.modal-content')
        //
            // .load('http://localhost/Admin/vertical-green/index.html')
            .load($(this).attr('href'), function(response, status,xhr){
                $('.chosen-select').chosen()
                if ( status == "error" ) {
                $('#theModal').modal('show').find('.modal-content').html('<div class="modal-body">Sorry but there was an error : <strong>'+xhr.status+'</strong> '+xhr.statusText+'</div>');
                    console.log( xhr.statusText );
                }
            });
        });

    });
</script>
