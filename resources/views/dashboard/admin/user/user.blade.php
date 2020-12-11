<form action="{{ route('admin.user_add') }}" method="post">
    @csrf
        @error('userExist')
            <div>{{ $message }}</div>
        @enderror
    <label for="fullname">Nama Penuh</label>
    <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="{{ old('username') }}">
        @error('username')
            <div>{{ $message }}</div>
        @enderror
    <label for="email">E-mel</label>
    <input type="text" name="email" id="email" value="{{ old('email') }}">
        @error('email')
            <div>{{ $message }}</div>
        @enderror
    <label for="password">Kata Laluan</label>
    <input type="password" name="password" id="password">
    <label for="password">Sahkan Kata Laluan</label>
    <input type="password" name="password_confirmation" id="password_confirmation">
        @error('password')
            <div>{{ $message }}</div>
        @enderror
    <label for="role">Peranan</label>
    <select name="role" id="role">
        <option value="student">Pelajar</option>
        <option value="lecturer">Pensyarah</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Tambah Pengguna</button>
</form>