<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    <title>@yield('page') - Skrip Instalasi Sistem - {{ env('APP_NAME') }}</title>
</head>
<body>
    <div class="container-fluid m-0 p-0 d-flex justify-content-center">
        @yield('content')
    </div>
</body>
</html>