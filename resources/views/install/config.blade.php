@extends('install.layout')
@section('page') Konfigurasi Sistem @endsection
@section('content')
<div class="container d-flex flex-column h-auto align-items-center bg-primary my-5 text-light rounded-3 shadow-lg">
    <h1 class="mt-5">Skrip Instalasi Sistem - {{ env('APP_NAME') }}</h1>
    <h2>Konfigurasi</h2>
    <hr class="border-light w-100 border-4">
    <div class="form-list w-50 mb-5 d-flex flex-column justify-content-around">
        <form action="{{ route('install.config') }}" method="POST">
            {{-- Admin account 
            Username: admin (Default: User cannot choose)
            Password: (User could choose)
            --}}
            @csrf
            <h3 class="mt-2">Akaun Admin</h3>
            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" name="adminFullName" id="adminFullname" placeholder="" value="{{ old('adminFullName') }}">
                <label class="text-primary" for="adminFullName">Nama Penuh</label>
            </div>
            @error('adminFullName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="adminEmailAddress" id="adminEmailAddress" placeholder="" value="{{ old('adminEmailAddress') }}">
                <label class="text-primary" for="adminEmailAddress">Alamat Emel</label>
            </div>
            @error('adminEmailAddress')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="" value="{{ old('password') }}">
                <label class="text-primary" for="password">Kata Laluan</label>
            </div>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="">
                <label class="text-primary" for="password_confirmation">Sahkan Kata Laluan</label>
            </div>
            {{-- Institute details
            Nama Institut
            Alamat Institut
            Alamat Emel Institut
            Nombor Telefon Institut
            Fax Institut
            --}}
            <h3 class="mt-5">Maklumat Institut</h3>
            <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" name="instituteName" id="instituteName" placeholder="" value="{{ old('instituteName') }}">
                <label class="text-primary" for="instituteName">Nama</label>
            </div>
            @error('instituteName')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="instituteAddress" id="instituteAddress" placeholder="" value="{{ old('instituteAddress') }}">
                <label class="text-primary" for="instituteAddress">Alamat</label>
            </div>
            @error('instituteAddress')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="instituteEmailAddress" id="instituteEmailAddress" placeholder="" value="{{ old('instituteEmailAddress') }}">
                <label class="text-primary" for="instituteEmailAddress">Alamat Emel</label>
            </div>
            @error('instituteEmailAddress')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="institutePhoneNumber" id="institutePhoneNumber" placeholder="" value="{{ old('institutePhoneNumer') }}">
                <label class="text-primary" for="institutePhoneNumber">Nombor Telefon</label>
            </div>
            @error('institutePhoneNumber')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="instituteFaxNumber" id="instituteFaxNumber" placeholder="" value="{{ old('instituteFaxNumber') }}">
                <label class="text-primary" for="instituteFaxNumber">Nombor Fax</label>
            </div>
            @error('instituteFaxNumber')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn w-50 my-5 hvr-shrink btn-outline-light fs-4">Pasang <i class="bi bi-gear"></i></button>
        </form>
    </div>
</div>
@endsection