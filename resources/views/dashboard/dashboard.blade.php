@extends('dashboard.layout.main')
@section('content')
    <h1 class="mt-5">Dashboard</h1>
    {{ 'NAMA PENUH: ' . strtoupper(Auth::user()->fullname) }}
    <br>
    {{ 'E-MEL: ' . strtoupper(Auth::user()->email) }}
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-primary">Log Keluar</button>
    </form>
@endsection