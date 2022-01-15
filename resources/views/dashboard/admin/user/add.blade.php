@extends('dashboard.layout.admin')
@section('content')
<div class="w-100 h-100 mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-11 col-lg-10">
            <form action="" method="post" enctype="multipart/form-data" class="mt-2 mb-2">
                @csrf
                <h2 class="text-left">Tambah Pengguna Secara Pukal</h2>
                @if(session()->has('userBulkAddSuccess'))
                    <div class="alert alert-success">{{ session('userBulkAddSuccess') }}</div>
                @endif
                <input type="file" name="user-xlsx" id="user-xlsx" class="form-control mb-3">
                    @error('user-xlsx')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('unsupportedType')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
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
