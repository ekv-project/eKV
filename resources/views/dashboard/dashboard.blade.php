<h1>Dashboard</h1>

@auth
    {{ 'NAMA PENUH: ' . strtoupper(Auth::user()->fullname) }}
    <br>
    {{ 'E-MEL: ' . strtoupper(Auth::user()->email) }}
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">Log Keluar</button>
    </form>
@endauth