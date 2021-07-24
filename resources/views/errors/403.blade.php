<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="api-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-300.png') }}" type="image/png">
    @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-def-300.png') }}" type="image/png">
    @endif
    <title>403 - {{ env('APP_NAME') }} | 
        @isset($settings)
            @empty($settings['institute_name'])
                Kolej Vokasional Malaysia
            @else
                {{ ucwords($settings['institute_name']) }}
            @endempty        
        @else   
            Kolej Vokasional Malaysia
        @endisset   
    </title>
</head>
<body>
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center mx-0 w-100 min-vh-100">
        <div class="d-flex">
            @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                <a href="{{ route('dashboard') }}" class="ms-3">
                    <img style="width: 10em; height: 10em; margin: 0.1em;" src="{{ asset('storage/img/system/logo-300.png') }}" alt="eKV Logo">
                </a>
            @endif
            @if(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
                <a href="{{ route('dashboard') }}" class="ms-3">
                    <img style="width: 10em; height: 10em; margin: 0.1em;" src="{{ asset('storage/img/system/logo-def-300.png') }}" alt="eKV Logo">
                </a>
            @endif
        </div>
        <h1 class="fw-bold mt-5">Error {{ $exception->getStatusCode() }}</h1>
        <h5 class="mt-2 mb-5">{{ $exception->getMessage() }}</h5>
        @if(isset($_SERVER['HTTP_REFERER']))
            <a class="btn btn-primary btn-lg" href="{{ url()->previous() }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i> Kembali</a>
        @else
            <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i> Kembali Ke Dashboard</a>
        @endif
    </div>
</body>
