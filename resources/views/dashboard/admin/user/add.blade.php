@extends('dashboard.layout.admin')
@section('content')
<div class="w-100 h-100 mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-11 col-lg-10">
            <form action="" method="post" enctype="multipart/form-data" class="mt-2 mb-2">
                @csrf
                <h1 class="text-center fs-2">Tambah Pengguna Secara Pukal</h1>
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
                <h3>Tambah Pengguna</h3>
                @csrf
                @error('userExist')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @if(session()->has('userAddSuccess'))
                    <div class="alert alert-success">{{ session('userAddSuccess') }}</div>
                @endif
                <label for="fullname" class="form-label">Nama Penuh</label>
                <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}" class="form-control">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control">
                    @error('username')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                <label for="email" class="form-label">E-mel</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                <label for="password" class="form-label">Kata Laluan</label>
                <input type="password" name="password" id="password" class="form-control">
                <label for="password" class="form-label">Sahkan Kata Laluan</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                <label for="role" class="form-label">Peranan</label>
                <select name="role" id="role" class="form-select">
                    <option value="student">Pelajar</option>
                    <option value="lecturer">Pensyarah</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn btn-primary mt-3 w-100" name="addOne">Tambah Pengguna</button>
            </form>
        </div>
    </div>
</div>
@endsection