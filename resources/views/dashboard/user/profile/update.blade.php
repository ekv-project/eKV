@extends('dashboard.layout.main')
@section('allowed')
    @auth
        Kemas Kini Profil
    @endauth
@endsection
@section('not-allowed')
    @auth
        <div class="error">
            <p>Anda tiada akses pada page ini!</p>
        </div>
    @endauth
@endsection