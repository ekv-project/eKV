<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container-fluid flex">
        <p>403</p>
        {{ $exception->getMessage() }}
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali Ke Dashboard</a>
    </div>
</body>