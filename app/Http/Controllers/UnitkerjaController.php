<?php

namespace App\Http\Controllers;

use App\Unitkerja;
use Illuminate\Http\Request;

class UnitkerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::denies('pegawai')) {
            return view('Unitkerja.index', [
                'data' => Unitkerja::paginate(),
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
    public function create()
    {
        if (\Gate::allows('admin') || \Gate::allows('pimpinan')) {
            dd('Admin allowed');
        } else {
            dd('You are not Admin');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unitkerja  $unitkerja
     * @return \Illuminate\Http\Response
     */
    public function show(Unitkerja $unitkerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unitkerja  $unitkerja
     * @return \Illuminate\Http\Response
     */
    public function edit(Unitkerja $unitkerja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unitkerja  $unitkerja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unitkerja $unitkerja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unitkerja  $unitkerja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unitkerja $unitkerja)
    {
        //
    }
}
