<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Split Bill App</title>
</head>
<body>

    <h1>🍽️ Split Bill App</h1>
    <p>Masukkan nama orang yang ikut makan</p>

    <form method="POST" action="/people">
        @csrf

        <input type="text" name="people[]" placeholder="Nama Orang 1" required><br><br>
        <input type="text" name="people[]" placeholder="Nama Orang 2" required><br><br>
        <input type="text" name="people[]" placeholder="Nama Orang 3"><br><br>

        <button type="submit">Lanjut</button>
    </form>

</body>
</html>
