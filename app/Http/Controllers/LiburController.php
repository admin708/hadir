<?php

namespace App\Http\Controllers;

use App\Libur;
use Illuminate\Http\Request;

class LiburController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Libur.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Libur  $libur
     * @return \Illuminate\Http\Response
     */
    public function show(Libur $libur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Libur  $libur
     * @return \Illuminate\Http\Response
     */
    public function edit(Libur $libur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Libur  $libur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Libur $libur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Libur  $libur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Libur $libur)
    {
        //
    }
}
