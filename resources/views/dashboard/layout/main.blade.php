<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="api-token" content="
    @isset($apiToken)
        @empty($apiToken)
            NULL
        @else
            {{$apiToken['api_token']}}
        @endempty 
    @endisset        
    ">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ $page }} - {{ env('APP_NAME') }} | 
        @isset($settings)
            @empty($settings['institute_name'])
                Kolej Vokasional Malaysia
            @else
                {{ $settings['institute_name'] }}
            @endempty        
        @else   
            Kolej Vokasional Malaysia
        @endisset   
    </title>
</head>
<body>
    <div class="container-fluid">
        @yield('content')
    </div>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
</body>
</html>