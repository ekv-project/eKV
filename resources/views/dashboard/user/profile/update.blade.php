@extends('dashboard.layout.main')
@section('content')
    {{-- Only allows current authenticated user to update their own profile --}} 
    
    <div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0">
        <div class="d-flex flex-column align-items-center">
            {{-- Only student can update their profile while others can only update their profile picture and password --}}
            {{-- Update Profile Picture --}}
            <form action="" method="post" enctype="multipart/form-data" class="mt-2 mb-2">
                @csrf
                <h2>Kemas Kini Gambar Profil</h2>
                @if(session()->has('pictureSuccess'))
                    <div class="alert alert-success">{{ session('pictureSuccess') }}</div>
                @endif
                <input type="file" name="profile-picture" id="profile-picture" class="form-control mb-3">
                @error('profile-picture')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('unsupportedType')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary w-100" name="picture">Kemas Kini</button>
            </form>
            {{-- Update Password --}}
            <form action="" method="post" class="mt-2 mb-2">
                @csrf
                <h2>Ubah Kata Laluan</h2>
                @if(session()->has('passwordUpdateSuccess'))
                    <div class="alert alert-success">{{ session('passwordUpdateSuccess') }}</div>
                @endif
                <div class="form-floating mb-3">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="current_password" value="{{ old('current_password') }}">
                    <label for="current_password" class="form-label">Kata Laluan Semasa</label>
                    @error('current_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('currentPasswordUpdate')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="new_password" value="{{ old('new_password') }}">
                    <label for="new_password" class="form-label">Kata Laluan Baharu</label>
                    @error('new_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('newPasswordUpdate')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="new_password_confirmation">
                    <label for="new_password_confirmation" class="form-label">Sahkan Kata Laluan Baharu</label>
                    @error('new_password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100" name="password">Ubah</button>
            </form>
            @can('authStudent')
                {{-- Update Profile --}}
                <form action="" method="post" class="mt-2 mb-2">
                    @csrf
                    <h2>Kemas Kini Profil</h2>
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
                        <input type="text" name="home_number" id="home_number" class="form-control" placeholder="012-3456789" value={{ old('home_number') }}>
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
                    <button type="submit" class="btn btn-primary w-100" name="profile">Kemas Kini</button>
                </form>
            @endcan
        </div>
    </div>
@endsection
