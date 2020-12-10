<form action="{{ route('admin.user_add') }}" method="post">
    @csrf
    <label for="fullname">Nama Penuh</label>
    <input type="text" name="fullname" id="fullname">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="email">E-mel</label>
    <input type="text" name="email" id="email">
    <label for="password">Kata Laluan</label>
    <input type="password" name="password" id="password">
    <label for="password">Sahkan Kata Laluan</label>
    <input type="password" name="password_confirmation" id="password_confirmation">
    <label for="role">Peranan</label>
    <select name="role" id="role">
        <option value="student">Pelajar</option>
        <option value="lecturer">Pensyarah</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Tambah Pengguna</button>
</form>