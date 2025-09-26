@extends('errors::hadir-layout')

@section('title', __('Outside The Network'))
@section('code')
&#129488;
@endsection
@section('image')
<img src="{{ asset('') }}assets/img/sample/photo/server_down.svg" alt="alt" class="imaged w-100 square mb-4">
@endsection
@section('message', __($exception->getMessage() ?: 'Outside The Network'))
