<form action="{{ route('login') }}" method="post">
    @csrf
    <h1>Log Masuk</h1>
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="{{ old('username') }}">
    @error('username')
        <div>{{ $message }}</div>
    @enderror
    <label for="username">Kata Laluan</label>
    <input type="password" name="password" id="password">
    @error('password')
        <div>{{ $message }}</div>
    @enderror
    <button type="submit">Log Masuk</button>
</form>