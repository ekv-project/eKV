<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ $page }} | {{ env('APP_NAME') }} {{ 'Kolej Vokasional Malaysia' }}</title>
</head>
<body>
    <div class="container-fluid">
        @yield('content')
        <script src="{{ asset('js/app.js') }}"></script>
    </div>
</body>
</html>