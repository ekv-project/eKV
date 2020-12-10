<form action="{{ route('login') }}" method="post">
    @csrf
    <h1>Log Masuk</h1>
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="username">Kata Laluan</label>
    <input type="password" name="password" id="password">
    <button type="submit">Log Masuk</button>
</form>