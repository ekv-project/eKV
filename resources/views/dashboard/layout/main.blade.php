<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if(isset(Auth::user()->api_token) AND Auth::user()->api_token != NULL))
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    @if(Storage::disk('local')->exists('public/img/system/logo-16.png'))
        <link rel="shortcut icon" href="{{ asset('public/img/system/logo-16.png') }}" type="image/png">
    @elseif(Storage::disk('local')->exists('public/img/system/logo-def-16.jpg'))
        <link rel="shortcut icon" href="{{ asset('public/img/system/logo-def-16.jpg') }}" type="image/jpeg">
    @endif
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
    {{-- Hamburger Menu Button --}}
    <div class="d-flex d-lg-none d-md-flex position-fixed top-0 start-0 hamburger-menu" style="z-index: 100">
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
    </div>
    <header class="container-fluid m-0 row d-flex align-items-center bg-primary position-fixed top-0 start-0" style="z-index: 80">
        {{-- Desktop Navbar --}}
        <div class="container-fluid d-flex align-items-center">
            <div class="container-fluid d-none d-lg-flex d-sm-none justify-content-around">
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('transcript.student', ['studentID' => Auth::user()->username]) }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="" class="btn button-transparent fs-5 text-light fw-normal">Pilihan Raya</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
            </div>
        </div>
        <div class="col-md-5 m-0 d-flex align-items-center justify-content-center">
            <div class="w-100 d-flex align-items-center">
                @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                    <img style="width: 2.5em; height: 2.5em; margin: 1em;" src="{{ asset('public/img/system/logo-300.png') }}" alt="Insitite Logo">
                @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.jpg'))
                    <img style="width: 2.5em; height: 2.5em; margin: 0.5em;" src="{{ asset('public/img/system/logo-def-300.jpg') }}" alt="Insitite Logo">
                @endif
                @isset($settings)
                    @empty($settings['institute_name'])
                        <h1 class="text-light fw-bold fs-4">Kolej Vokasional Malaysia</h1>
                    @else
                        <h1 class="text-light fw-bold fs-4">{{ ucwords($settings['institute_name']) }}</h1>
                    @endempty        
                @else
                    <h1 class="text-light fw-bold fs-4">Kolej Vokasional Malaysia</h1>
                @endisset  
            </div>
        </div>
        <div class="col-md-4 m-0 invisible"></div>
        <div class="col-md-3 m-0 d-flex align-items-center justify-content-around">
            @if(Storage::disk('local')->exists('public/img/profile/'. Auth::user()->username . '.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em;" src="{{ asset('public/img/profile/'. Auth::user()->username . '.jpg') }}" alt="User Profile Picture" class="img-fluid rounded-circle hvr-shrink" style="height: 3em">
                </a>
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em;" src="{{ asset('public/img/profile/default/def-300.jpg') }}" alt="Default Profile Picture" class="img-fluid rounded-circle hvr-grow hvr-shrink" style="height: 3em">
                </a>
            @endif
            <a href="{{ route('profile') }}" class="text-light fw-bold text-decoration-none hvr-grow">{{ Auth::user()->username }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-dark  hvr-shrink">Log Keluar</button>
            </form>
        </div>
    </header>
    {{-- Mobile Navbar --}}
    <div class="d-flex flex-column d-lg-none invisible position-fixed top-0 start-0 bg-primary h-100 w-100 m-0 justify-content-center align-items-center overflow-auto hamburger-menu-list" style="z-index: 80">
        <div class="d-flex flex-column justify-content-around align-items-center w-100 h-100">
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('transcript.student', ['studentID' => Auth::user()->username]) }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="" class="btn button-transparent fs-5 text-light fw-normal">Pilihan Raya</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
        </div>
    </div>
    <div class="container-fluid w-100 mt-6 mt-sm-6 mt-md-6 mt-lg-6">
        @yield('content')
    </div>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
    {{-- <footer class="d-flex flex-row align-content-center row bg-primary">
        <p>Hak Cipta &copy; Muhammad Hanis Irfan dan Muhammad Firdaus 2020</p>
    </footer> --}}
</body>
</html>