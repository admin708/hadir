<?php

namespace App\Http\Controllers;
use App\Hadir;
use Illuminate\Http\Request;

class HadirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            $data = Hadir::get();
            return view('Presensi.index', [
                'data' => $data
            ]);
        } else {
            return 'hanya punya dirinya nanti dipasangi guard';
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            $data = Hadir::get();
            return view('Presensi.index', [
                'data' => $data
            ]);
        } else {
            return 'hanya punya dirinya nanti dipasangi guard';
        }
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $image_64 = $request->image; //your base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

      // find substring fro replace here eg: data:image/png;base64,
       $image = str_replace($replace, '', $image_64);
       $image = str_replace(' ', '+', $image);
       $imageName = auth()->id().'_'.\Str::random(10).'.'.$extension;
       $upload = \File::put(public_path('storage/foto-kehadiran'). '/' . $imageName, base64_decode($image));
       if ($upload) {
           Hadir::create([
            'user_id' => auth()->id(),
            'foto_masuk' => $imageName,
            'ip_location' => \request()->ip()
           ]);
           return \response(['status' => 200, 'pesan' => "Sukses"]);
       } else {
        return \response(['status' => 400, 'pesan' => 'Gagagl Kirim Kehadiran.']);
       }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
