@extends('dashboard.layout.main')
@section('content')
    @if(isset($profile))
        No. Kad Pengenalan: {{ $profile['identification_number'] }} <br>
        No. Telefon Peribadi: {{ $profile['phone_number'] }} <br>
        Tarikh Lahir: {{ $profile['date_of_birth'] }} <br>
        Tempat Lahir: {{ ucwords($profile['place_of_birth']) }} <br>
        Alamat Rumah: {{ ucwords($profile['home_address']) }} <br>
        Nama Penjaga: {{ ucwords($profile['guardian_name']) }} <br>
        Nombor Telefon Penjaga: {{ $profile['guardian_phone_number'] }} <br>
    @else
        No. Kad Pengenalan:  <br>
        No. Telefon Peribadi:  <br>
        Tarikh Lahir:  <br>
        Tempat Lahir:  <br>
        Alamat Rumah:  <br>
        Nama Penjaga:  <br>
        No. Telefon Penjaga:  <br>
    @endif
    @if(Storage::disk('local')->exists('public/img/profile/'. $username . '.jpg'))
        <img src="{{ asset('public/img/profile/'. $username . '.jpg') }}" alt="User Profile Picture" class="img-fluid rounded-circle" style="height: 7em">
    @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.jpg'))
        <img src="{{ asset('public/img/profile/default/def-300.jpg') }}" alt="Default Profile Picture" class="img-fluid rounded-circle" style="height: 7em">
    @endif 
    @can('authUser', $username)
        <a href="{{ route('profile.update', ['username' => $username]) }}" class="btn btn-primary">Kemas Kini</a>  
    @endcan 
@endsection
