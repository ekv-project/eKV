@extends('dashboard.layout.main')
@section('content')
    @auth
        {{-- Only allows current authenticated user to update their own profile --}} 
        @if(Gate::allows('authUser', $username))
            {{ $username }}
        @elseif(Gate::denies('authUser', $username))
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
