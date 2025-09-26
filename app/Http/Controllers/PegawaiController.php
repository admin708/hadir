<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use App\Imports\PegawaiImport;
use App\Exports\DataPegawaiExport;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::denies('pegawai')) {
            return view('Pegawai.index', [
                'data' => User::paginate(),
            ]);
        } else {
            abort(403, 'You are not allowed');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function ajax(){
        // return Datatables::of(User::all())->make(true);
        $data = User::all();
        DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
        return DataTables::of($data)
            ->editColumn("created_at", function ($data) {
                return $data->created_at->translatedFormat('d M Y');
            })
            ->addColumn('aksi', function ($data) {
                if (\Gate::allows('pimpinan')) {
                   $update = '<a href="'.route('pegawai.edit', $data->id).'" class="btn btn-primary btn-sm">Edit</a>
                   <a href="'.route('pegawai.destroy', $data->id).'" class="btn btn-danger btn-sm">Delete</a>
                   <a href="'.route('pegawai.reset', $data->id).'" class="btn btn-warning btn-sm">Reset</a>
                   ';
                }else{
                    $update = '<a href="'.route('pegawai.edit', $data->id).'" class="btn btn-primary btn-sm">Edit</a>
                    <a href="'.route('pegawai.reset', $data->id).'" class="btn btn-warning btn-sm">Reset</a>';
                }
                return $update;
            })
            ->addIndexColumn()
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            dd('Admin allowed');
        } else {
            dd('You are not Admin');
        }
    }

    public function import()
    {
        if (request()->has('file')) {
            // request()->validate([
            //     'file' => 'required|mimes:xls, xlsx, csv'
            // ]);
            try {
                $excel = Excel::import(new PegawaiImport, request()->file('file'));
                return response()->json(['status' => 200]);
            } catch (\Illuminate\Database\QueryException $th) {
                return response()->json(['status' => 400, 'message' => 'Terdapat Data Duplikat', 'pea' => $th]);
            }
        } else {
            $file = public_path(). "/file/templatePegawai.xlsx";
            // return $file;
            $headers = array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename=templatePegawai',
                );
                if (file_exists($file) ) {
                    // Send Download
                    return \Response::download($file, 'templatePegawai.xlsx', $headers);
                } else {
                    // Error
                    exit('Requested file does not exist on our server!' );
                }
            return response()->download($pathToFile, $name, $headers);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            dd('Admin allowed');
        } else {
            dd('You are not Admin');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function show(User $pegawai)
    {
       //
    }

    public function export()
    {
        $unit = \App\Unitkerja::find(request()->unitkerja_id);
        $unitkerja = request()->unitkerja_id;

        $title = 'Data Pegawai '. ($unit == null ? 'Semua Unit Kerja':'Unit Kerja '.$unit->nama);
        // dd($title);
        return Excel::download(new DataPegawaiExport($unitkerja), $title.'.csv');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit(User $pegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $pegawai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $pegawai)
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            dd('Admin allowed');
        } else {
            dd('You are not Admin');
        }
    }
}
