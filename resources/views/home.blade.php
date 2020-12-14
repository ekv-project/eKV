<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>e-KV - Log Masuk</title>
</head>
<body>
    <div class="container-fluid d-flex flex-column justify-content-center p-0 m-0 vh-100">
        <div class="container-fluid row">
            <div class="d-flex flex-column justify-content-center col-sm">
                <h1>Sistem Maklumat Pelajar</h1>
                <h1>Kolej Vokasional</h1>
            </div>
            <form action="{{ route('login') }}" method="post" class="col-lg">
                @csrf
                <h1>Log Masuk</h1>
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
                <button type="submit" class="btn btn-primary w-100 mt-2">Log Masuk</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>