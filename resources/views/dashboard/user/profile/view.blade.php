@extends('dashboard.layout.main')
@section('allowed')
    @auth
        {{ auth()->user()->username }}
    @endauth
@endsection
@section('not-allowed')
    @auth
        <div class="error">
            <p>Anda tiada akses pada page ini!</p>
        </div>
    @endauth
@endsection