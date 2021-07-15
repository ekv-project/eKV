<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center mx-0 w-100 min-vh-100">
        <div class="d-flex">
            @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                <a href="{{ route('dashboard') }}" class="ms-3">
                    <img style="width: 10em; height: 10em; margin: 0.1em;" src="{{ asset('public/img/system/logo-300.png') }}" alt="Insitite Logo">
                </a>
            @endif
            @if(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
                <a href="{{ route('dashboard') }}" class="ms-3">
                    <img style="width: 10em; height: 10em; margin: 0.1em;" src="{{ asset('public/img/system/logo-def-300.png') }}" alt="Insitite Logo">
                </a>
            @endif
        </div>
        <h1 class="fw-bold mt-5">Error {{ $exception->getStatusCode() }}</h1>
        <h5 class="mt-2 mb-5">{{ $exception->getMessage() }}</h5>
        <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" class="btn btn-primary">Kembali Ke Dashboard</a>
    </div>
</body>
