<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @php
        if(!empty($settings)){
            if(!empty($settings['institute_name'])){
                $instituteName = ucwords($settings['institute_name']);
            }else{
                $instituteName = "Kolej Vokasional Malaysia";
            }
        }else{
            $instituteName = "Kolej Vokasional Malaysia";
        }
    @endphp
    <meta name="description" content="{{ 'Sistem maklumat pelajar - ' . $instituteName . '.'}}" >
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-300.png') }}" type="image/png">
    @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-def-300.png') }}" type="image/png">
    @endif
    <title>{{ $page }} - {{ env('APP_NAME') }} | {{ $instituteName }}</title>
</head>
<body>
    <div class="container-fluid d-flex flex-column justify-content-center p-0 m-0 vh-100">
        <div class="container-fluid row">
            <div class="d-flex flex-column justify-content-center col-sm">
                <h1>Sistem Maklumat Pelajar</h1>
                @isset($settings)
                    @empty($settings['institute_name'])
                        <h2>Kolej Vokasional Malaysia</h2>
                    @else
                        <h2>{{ ucwords($settings['institute_name']) }}</h2>
                    @endempty
                @else
                    <h2>Kolej Vokasional Malaysia</h2>
                @endisset
            </div>
            <form action="{{ route('login') }}" method="post" class="col-lg">
                @csrf
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control">
                @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="password" class="form-label">Kata Laluan</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary w-100 mt-2 hvr-shrink"><i class="bi bi-box-arrow-in-right" style="margin-right: 1em"></i>Log Masuk</button>
            </form>
        </div>
        <div class="container-fluid row flex-column justify-content-center align-items-center">
            <div class="mt-4 d-flex flex-column justify-content-center align-items-center col-12">
                <p class="text-center fw-bold"><a href="https://github.com/hadiirfan/eKV" class="hvr-shrink text-decoration-none">Sistem eKV</a></p>
                <p class="text-center">Hak Cipta Terpelihara &copy; Muhammad Hanis Irfan bin Mohd Zaid & Amirah Hadirah Aina binti Ramlan Jafery 2021-2022</p>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
