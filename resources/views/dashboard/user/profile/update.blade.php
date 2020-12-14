@extends('dashboard.layout.main')
@section('content')
    @auth
        {{-- Only allows current authenticated user to update their own profile --}} 
        @if(Gate::allows('authUser', $username))
            <div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0">
                <div class="d-flex align-items-center">
                    <form action="" method="post">
                        @csrf
                        <h2>Kemas Kini Profil Pelajar</h2>
                        @if (session()->has('profileUpdateSuccess'))
                            <div class="alert alert-success">{{ session('profileUpdateSuccess') }}</div>
                        @endif
                        <div class="form-floating mb-3">
                            <input type="text" name="identification_number" id="identification_number" class="form-control" placeholder="012-3456789" value="{{ old('identification_number') }}">
                            <label for="identification_number" class="form-label">No. Kad Pengenalan</label>
                            <div class="form-text">
                                Format: XXXXXX-XX-XXXX
                            </div>
                            @error('identification_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="012-3456789" value="{{ old('phone_number') }}">
                            <label for="phone_number" class="form-label">No. Telefon Peribadi</label>
                            <div class="form-text">
                                Format: +6012-3456789
                            </div>
                            @error('phone_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="012-3456789" value="{{ old('date_of_birth') }}">
                            <label for="date_of_birth" class="form-label">Tarikh Lahir</label>
                            @error('date_of_birth')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="012-3456789" value="{{ old('place_of_birth') }}">
                            <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                            @error('place_of_birth')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="home_address" id="home_address" class="form-control" placeholder="012-3456789" value="{{ old('home_address') }}">
                            <label for="home_address" class="form-label">Alamat Rumah</label>
                            @error('home_address')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="home_number" id="home_number" class="form-control" placeholder="012-3456789" value="{{ old('home_number') }}">
                            <label for="home_number" class="form-label">No. Telefon Rumah</label>
                            <div class="form-text">
                                Format: +601-2345678
                            </div>
                            @error('home_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="guardian_name" id="guardian_name" class="form-control" placeholder="012-3456789" value="{{ old('guardian_name') }}">
                            <label for="guardian_name" class="form-label">Nama Penjaga</label>
                            @error('guardian_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="guardian_phone_number" id="guardian_phone_number" class="form-control" placeholder="012-3456789" value="{{ old('guardian_phone_number') }}">
                            <label for="guardian_phone_number" class="form-label">No. Telefon Penjaga</label>
                            <div class="form-text">
                                Format: +6012-3456789
                            </div>
                            @error('guardian_phone_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kemas Kini</button>
                    </form>
                </div>
            </div>
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
