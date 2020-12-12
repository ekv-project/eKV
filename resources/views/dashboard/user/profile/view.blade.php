@extends('dashboard.layout.main')
@section('content')
    @auth
        {{-- Only allows current authenticated user to view their own profile + admin + their coordinator --}} 
        @if(Gate::allows('authUser', $username) || Gate::allows('authAdmin', $username) || Gate::allows('authCoordinator', $username))
            {{ $username }}
        @elseif(Gate::denies('authUser', $username) || Gate::allows('authAdmin', $username) || Gate::allows('authCoordinator', $username))
            <div class="error">
                <p>Anda Tiada Akses Pada Laman Ini!</p>
            </div>
        @endif
    @endauth
    @guest
        <div class="error">
            <p>Sila Log Masuk!</p>
        </div>
    @endguest
@endsection
