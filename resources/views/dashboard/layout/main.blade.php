<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - {{ $page }} - {{ env('APP_NAME') }}</title>
</head>
<body>
    @auth
        @if($permission == true)
            @yield('allowed')
        @elseif($permission == false)
            @yield('not-allowed')
        @endif
    @endauth
    @guest
        <div class="error">
            <p>Sila log masuk!</p> 
        </div>
    @endguest
</body>
</html>