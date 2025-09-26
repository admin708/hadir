<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @laravelPWA
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body{
            background-color: white !important;
        }
    </style>
</head>
<body>
    <div class="container vh-100">
      <div class="row vh-100 d-flex align-items-center justify-content-center">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto text-danger">
            <img class="img-fluid rounded mx-auto d-block" src="{{ asset('images/offline.png') }}" alt="SIKPEG">
        </div>
      </div>
    </div>
  </body>
</html>
