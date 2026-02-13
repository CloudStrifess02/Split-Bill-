<!DOCTYPE html>
<html>
<head>
    <title>Orang & Menu</title>
</head>
<body>

<h2>👥 Masukkan Nama Orang</h2>

<form method="POST" action="/choose-people">
@csrf

@foreach ($menu as $index => $item)
    <input type="hidden" name="menu[]" value="{{ $item }}">
    <input type="hidden" name="price[]" value="{{ $price[$index] }}">
@endforeach

<input type="text" name="people[]" placeholder="Nama Orang 1" required><br>
<input type="text" name="people[]" placeholder="Nama Orang 2" required><br>
<input type="text" name="people[]" placeholder="Nama Orang 3"><br><br>

<button type="submit">Lanjut Pilih Menu</button>
</form>

</body>
</html>
