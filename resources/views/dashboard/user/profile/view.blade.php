@extends('dashboard.layout.main')
@section('content')
    @auth
        {{-- Only allows current authenticated user to view their own profile + admin + superadmin + their coordinator --}} 
        @if(Gate::allows('authUser', $username) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin') || Gate::allows('authCoordinator', $username))
            @if(isset($profile))
                No. Kad Pengenalan: {{ $profile['identification_number'] }} <br>
                No. Telefon Peribadi: {{ $profile['phone_number'] }} <br>
                Tarikh Lahir: {{ $profile['date_of_birth'] }} <br>
                Tempat Lahir: {{ $profile['place_of_birth'] }} <br>
                Alamat Rumah: {{ $profile['home_address'] }} <br>
                Nama Penjaga: {{ $profile['guardian_name'] }} <br>
                No. Telefon Penjaga: {{ $profile['guardian_phone_number'] }} <br>
            @else
                No. Kad Pengenalan:  <br>
                No. Telefon Peribadi:  <br>
                Tarikh Lahir:  <br>
                Tempat Lahir:  <br>
                Alamat Rumah:  <br>
                Nama Penjaga:  <br>
                No. Telefon Penjaga:  <br>

            @endif
        @endif
    @endauth
    @guest
        <div class="error">
            <p>Sila Log Masuk!</p>
        </div>
    @endguest
@endsection
