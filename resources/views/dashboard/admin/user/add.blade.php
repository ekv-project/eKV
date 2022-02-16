@extends('dashboard.layout.admin')
@section('content')
<div class="w-100 h-100 mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-11 col-lg-10">
            <form action="" method="post" enctype="multipart/form-data" class="mt-2 mb-2">
                @csrf
                <h2 class="text-left">Tambah Pengguna Secara Pukal</h2>
                <a href="{{ asset('storage/spreadsheet/Tambah_Pengguna_Baharu.xlsx'); }}" class="btn btn-primary w-25 hvr-shrink my-3">Muat Turun Templat</a>
                @if(session()->has('userBulkAddSuccess'))
                    <div class="alert alert-success">{{ session('userBulkAddSuccess') }}</div>
                @endif
                <input type="file" name="user-xlsx" id="user-xlsx" class="form-control mb-3">
                @error('user-xlsx')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if(session()->has('spreadsheetErr'))
                    @if(count(session('spreadsheetErr')) > 0)
                        <div class="my-3 alert alert-danger">
                            <p class="fw-bold">Ralat ({{ count(session('spreadsheetErr')) }}):</p>
                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorCollapse" aria-expanded="false" aria-controls="errorCollapse"><i class="bi bi-arrows-expand"></i> Senarai Ralat</button>
                            <div class="collapse mt-3" id="errorCollapse">
                                <div class="card card-body">
                                    @for ($i = 0; $i < count(session('spreadsheetErr')); $i++)
                                        <p class="mt-1">{{ $i + 1 . ": " }}{{ session('spreadsheetErr')[$i] }}</p>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="addBulk">Tambah Pukal</button>
            </form>
        </div>
        <div class="col-11 col-lg-10">
            <form action="{{ route('admin.user.add') }}" method="post" class="mt-3 mb-5">
                <h2 class="text-left">Tambah Pengguna</h2>
                @csrf
                @error('userExist')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if(session()->has('userAddSuccess'))
                    <div class="alert alert-success">{{ session('userAddSuccess') }}</div>
                @endif
                <div class="form-floating mb-3">
                    <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control" placeholder="">
                    <label for="username" class="form-label">ID Pengguna</label>
                    @error('username')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}" class="form-control" placeholder="">
                    <label for="fullname" class="form-label">Nama Penuh</label>
                    @error('fullname')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select name="gender" id="gender" class="form-select mb-1">
                        <option value="male">Lelaki</option>
                        <option value="female">Perempuan</option>
                        <option value="notapplicable">Tidak Berkaitan</option>
                    </select>
                    <label for="gender" class="form-label">Jantina</label>
                    @error('gender')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="">
                    <label for="email" class="form-label">E-mel</label>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="">
                    <label for="password" class="form-label">Kata Laluan</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="">
                    <label for="password" class="form-label">Sahkan Kata Laluan</label>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
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
                <div class="form-floating">
                    <select name="role" id="role" class="form-select">
                        <option value="student">Pelajar</option>
                        <option value="lecturer">Pensyarah</option>
                        <option value="admin">Admin</option>
                    </select>
                    <label for="role" class="form-label">Peranan</label>
                    @error('role')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100" name="addOne">Tambah Pengguna</button>
            </form>
        </div>
    </div>
</div>
@endsection
