@extends('dashboard.layout.main')
@section('content')
    <div class="mt-6 d-flex flex-column justify-content-center align-items-center">
        @if(isset($profile))
        <div class="mt-3 d-flex flex-column justify-content-center align-items-center">
            @if(Storage::disk('local')->exists('public/img/profile/'. $username . '.jpg'))
                <img src="{{ asset('public/img/profile/'. $username . '.jpg') }}" alt="User Profile Picture" class="img-fluid rounded-circle" style="height: 5em; width: 5em;">
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.jpg'))
                <img src="{{ asset('public/img/profile/default/def-300.jpg') }}" alt="Default Profile Picture" class="img-fluid rounded-circle" style="height: 5em; width: 5em;">
            @endif 
            No. Kad Pengenalan: {{ $profile['identification_number'] }} <br>
            No. Telefon Peribadi: {{ $profile['phone_number'] }} <br>
            Tarikh Lahir: {{ $profile['date_of_birth'] }} <br>
            Tempat Lahir: {{ ucwords($profile['place_of_birth']) }} <br>
            Alamat Rumah: {{ ucwords($profile['home_address']) }} <br>
            Nama Penjaga: {{ ucwords($profile['guardian_name']) }} <br>
            Nombor Telefon Penjaga: {{ $profile['guardian_phone_number'] }} <br>
            <div class="d-flex justify-content-center align-items-center">
                @can('authUser', $username)
                <a href="{{ route('profile.update', ['username' => $username]) }}" class="btn btn-primary me-3">Kemas Kini</a>  
                @endcan
                @if(Gate::allows('authUser', $username) || Gate::allows('authCoordinator', $username) || Gate::allows('authAdmin'))
                    <a href="{{ route('profile.download', ['username' => $username]) }}" class="btn btn-primary">Muat Turun</a>     
                @endif
            </div>
            @else
                @can('authStudent')
                    No. Kad Pengenalan:  <br>
                    No. Telefon Peribadi:  <br>
                    Tarikh Lahir:  <br>
                    Tempat Lahir:  <br>
                    Alamat Rumah:  <br>
                    Nama Penjaga:  <br>
                    No. Telefon Penjaga:  <br>
                @endcan
            @endif
        </div>
    </div>
@endsection
