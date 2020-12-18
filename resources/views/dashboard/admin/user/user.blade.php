@extends('dashboard.layout.main')
@section('content')
    <form action="{{ route('admin.user_add') }}" method="post">
        @csrf
            @error('userExist')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        <label for="fullname" class="form-label">Nama Penuh</label>
        <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}" class="form-control">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control">
            @error('username')
                <div>{{ $message }}</div>
            @enderror
        <label for="email" class="form-label">E-mel</label>
        <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        <label for="password" class="form-label">Kata Laluan</label>
        <input type="password" name="password" id="password" class="form-control">
        <label for="password" class="form-label">Sahkan Kata Laluan</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        <label for="role" class="form-label">Peranan</label>
        <select name="role" id="role" class="form-select">
            <option value="student">Pelajar</option>
            <option value="lecturer">Pensyarah</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Tambah Pengguna</button>
    </form>
@endsection