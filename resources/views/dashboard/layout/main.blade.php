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
    <link rel="shortcut icon" href="
    {{-- Change this later --}}
    @isset($settings)
        @empty($settings['logo'])
            {{ asset('/storage/img/system/logo-def-300.png') }}
        @else
            {{ asset('/storage/img/system/logo-def-300.png') }}
        @endempty        
    @else   
        {{ asset('/storage/img/system/logo-def-300.png') }}
    @endisset   
    " type="image/png">
    <title>{{ $page }} - {{ env('APP_NAME') }} | 
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
    <header class="d-flex bg-primary">
        <div class="col-2 d-flex align-items-center justify-content-center">
            <h3 class="text-light fw-bold">{{ env('APP_NAME') }}</h3>
        </div>
        <div class="col-8 d-flex align-items-center justify-content-sm-around m-0">
            <a href="{{ route('dashboard') }}" class="text-light text-decoration-none fs-6 hvr-underline-from-center">Dashboard</a>
            <a href="{{ route('classroom') }}" class="text-light text-decoration-none fs-6 hvr-underline-from-center">Kelas</a>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-around">
            @if(Storage::disk('local')->exists('public/img/profile/'. Auth::user()->username . '.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img src="{{ asset('public/img/profile/'. Auth::user()->username . '.jpg') }}" alt="User Profile Picture" class="img-fluid mt-1 mb-1 rounded-circle img-thumbnail hvr-shrink" style="height: 3em">
                </a>
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img src="{{ asset('public/img/profile/default/def-300.jpg') }}" alt="Default Profile Picture" class="img-fluid mt-1 mb-1 rounded-circle img-thumbnail hvr-grow" style="height: 3em">
                </a>
            @endif
            <a href="{{ route('profile') }}" class="text-light fw-bold text-decoration-none hvr-grow">{{ Auth::user()->username }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-dark hvr-shrink">Log Keluar</button>
            </form>
        </div>
    </header>
    <div class="container-fluid">
        @yield('content')
    </div>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
    {{-- <footer class="d-flex flex-row align-content-center row bg-primary">
        <p>Hak Cipta &copy; Muhammad Hanis Irfan dan Muhammad Firdaus 2020</p>
    </footer> --}}
</body>
</html>