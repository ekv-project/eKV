@extends('dashboard.layout.main')
@section('content')
    {{-- Only allows current authenticated user to update their own profile --}} 
    
    <div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0">
        <div class="d-flex flex-column">
            {{-- Only student can update their profile while others can only update their profile picture and password --}}
            {{-- Update Profile Picture --}}
            <div class="row">
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
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="picture">Kemas Kini</button>
                </form>
            </div>
            {{-- Update Password --}}
            <div class="row">
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
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="password">Ubah</button>
                </form> 
            </div> 
            @can('authStudent')
                {{-- Update Profile --}}
                <div class="row">
                    <form action="" method="post" class="mt-2 mb-2">
                        @csrf
                        <h2>Kemas Kini Profil</h2>
                        @if (session()->has('profileUpdateSuccess'))
                            <div class="alert alert-success">{{ session('profileUpdateSuccess') }}</div>
                        @endif
                        <div class="form-floating mb-3">
                            <input type="text" name="identification_number" id="identification_number" class="form-control" placeholder="number" value="@php if(old('identification_number') !== null){echo old('identification_number');}elseif(isset($profile['identification_number'])){echo $profile['identification_number'];}else{echo NULL;} @endphp">
                            <label for="identification_number" class="form-label">No. Kad Pengenalan</label>
                            <div class="form-text">
                                Format: XXXXXX-XX-XXXX
                            </div>
                            @error('identification_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="phone" value="@php if(old('phone_number') !== null){echo old('phone_number');}elseif(isset($profile['phone_number'])){echo $profile['phone_number'];}else{echo NULL;} @endphp">
                            <label for="phone_number" class="form-label">No. Telefon Peribadi</label>
                            <div class="form-text">
                                Format: +6012-3456789
                            </div>
                            @error('phone_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="date" value="@php if(old('date_of_birth') !== null){echo old('date_of_birth');}elseif(isset($profile['date_of_birth'])){echo $profile['date_of_birth'];}else{echo NULL;} @endphp">
                            <label for="date_of_birth" class="form-label">Tarikh Lahir</label>
                            @error('date_of_birth')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="location" value="@php if(old('place_of_birth') !== null){echo old('place_of_birth');}elseif(isset($profile['place_of_birth'])){echo $profile['place_of_birth'];}else{echo NULL;} @endphp">
                            <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                            @error('place_of_birth')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="home_address" class="form-label">Alamat Rumah</label>
                            <textarea name="home_address" id="home_address" cols="3" class="form-control">@php if(old('home_address') !== null){echo old('home_address');}elseif(isset($profile['home_address'])){echo $profile['home_address'];}else{echo NULL;} @endphp</textarea>
                            @error('home_address')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="home_number" id="home_number" class="form-control" placeholder="number" value="@php if(old('home_number') !== null){echo old('home_number');}elseif(isset($profile['home_number'])){echo $profile['home_number'];}else{echo NULL;} @endphp">
                            <label for="home_number" class="form-label">No. Telefon Rumah</label>
                            <div class="form-text">
                                Format: +601-2345678
                            </div>
                            @error('home_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="guardian_name" id="guardian_name" class="form-control" placeholder="name" value="@php if(old('guardian_name') !== null){echo old('guardian_name');}elseif(isset($profile['guardian_name'])){echo $profile['guardian_name'];}else{echo NULL;} @endphp">
                            <label for="guardian_name" class="form-label">Nama Penjaga</label>
                            @error('guardian_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="guardian_phone_number" id="guardian_phone_number" class="form-control" placeholder="phone" value="@php if(old('guardian_phone_number') !== null){echo old('guardian_phone_number');}elseif(isset($profile['guardian_phone_number'])){echo $profile['guardian_phone_number'];}else{echo NULL;} @endphp">
                            <label for="guardian_phone_number" class="form-label">No. Telefon Penjaga</label>
                            <div class="form-text">
                                Format: +6012-3456789
                            </div>
                            @error('guardian_phone_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="profile">Kemas Kini</button>
                    </form>
                </div>       
            @endcan
        </div>
    </div>
@endsection