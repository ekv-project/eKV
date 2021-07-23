@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- Only the profile owner, coordinator and admin can view --}}
                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $username) || Gate::allows('authUser', $username))
                    <a href="{{ route('transcript.student', ['studentID' => $username]) }}" class="btn btn-primary"><i class="bi bi-eye"></i>Transkrip Peperiksaan</a>
                    <a href="{{ route('profile.download', ['username' => $username]) }}" class="btn btn-primary"><i class="bi bi-download"></i>Muat Turun</a>
                    <a href="{{ route('profile.update', ['username' => $username]) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Kemas Kini</a>
                @endif
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-5 w-100 d-flex flex-column justify-content-center align-items-center">
            <h1 class="text-center fs-2 my-2">Profil Pengguna</h1>
            @if(Storage::disk('local')->exists('public/img/profile/'. $username . '.png'))
                <img src="{{ asset('storage/img/profile/'. $username . '.png') }}" alt="User Profile Picture" class="img-fluid rounded-circle mt-1 mb-1 border-2 border-primary" style="height: 8em; width: 9em;">
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.png'))
                <img src="{{ asset('storage/img/profile/default/def-300.png') }}" alt="Default Profile Picture" class="img-fluid rounded-circle mt-1 mb-1 border-2 border-primary" style="height: 8em; width: 9em;">
            @endif 
            @if(isset($profile))
                <div class="row mt-1 mb-4">
                    <div class="row row-cols-1 row-cols-lg-2">
                        <p class="col fw-bold my-4"><i class="bi bi-person-lines-fill"></i>NAMA PENUH: <span class="fw-normal">{{ strtoupper($profile->fullname) }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-person-badge"></i>NO. KAD PENGENALAN: <span class="fw-normal">{{ $profile->identification_number }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-envelope-fill"></i>ALAMAT E-MEL: <span class="fw-normal">{{ strtoupper($profile->email) }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-phone-fill"></i>NO. TELEFON PERIBADI: <span class="fw-normal">{{ $profile->phone_number }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-calendar2-week-fill"></i>TARIKH LAHIR: <span class="fw-normal">{{ $profile->date_of_birth }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-building"></i>TEMPAT LAHIR: <span class="fw-normal">{{ strtoupper($profile->place_of_birth) }}</span></p>
                    </div>
                    <div class="row row-cols-1 row-cols-lg-2">
                        <p class="col fw-bold my-4"><i class="bi bi-house-door-fill"></i>ALAMAT RUMAH: <span class="fw-normal">{{ strtoupper($profile->home_address) }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-telephone-fill"></i>NO. TELEFON RUMAH: <span class="fw-normal">{{ $profile->home_number }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-person-lines-fill"></i>NAMA PENJAGA: <span class="fw-normal">{{ strtoupper($profile->guardian_name) }}</span></p>
                        <p class="col fw-bold my-4"><i class="bi bi-phone-fill"></i>NO. TELEFON PENJAGA: <span class="fw-normal">{{ $profile->guardian_phone_number }}</span></p>
                    </div>
                </div>
            @else
                <div class="row mt-1 mb-4">
                    <div class="row row-cols-1 row-cols-lg-2">
                        <p class="col fw-bold my-4">NAMA PENUH: <span class="fw-normal">{{ strtoupper($noProfile->fullname) }}</span></p>
                        <p class="col fw-bold my-4">ALAMAT E-MEL: <span class="fw-normal">{{ strtoupper($noProfile->email) }}</span></p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
