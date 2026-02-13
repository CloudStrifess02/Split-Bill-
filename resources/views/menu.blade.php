<!DOCTYPE html>
<html lang="en">
<head>
    <title>Split Bill</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8">
    <h1 class="text-2xl font-bold text-center mb-1">Split Bill</h1>
    <p class="text-center text-gray-500 mb-6">Tambah menu & jumlah porsi</p>

    <form method="POST" action="/menu" id="menuForm" class="space-y-4">
        @csrf

        <div id="menuContainer" class="space-y-3">
            <!-- MENU ITEM -->
            <div class="flex gap-2 items-center menu-item">
                <input type="text" name="menu[]" placeholder="Menu"
                    class="w-1/3 border rounded-lg px-3 py-2">

                <input type="number" name="price[]" placeholder="Harga"
                    class="w-1/4 border rounded-lg px-3 py-2">

                <input type="number" name="qty[]" placeholder="Qty" value="1" min="1"
                    class="w-1/6 border rounded-lg px-3 py-2">

                <button type="button"
                    onclick="removeMenu(this)"
                    class="text-red-500 font-bold text-xl px-2">
                    ×
                </button>
            </div>
        </div>

        <button type="button"
            onclick="addMenu()"
            class="w-full border border-dashed py-2 rounded-xl text-blue-600 font-semibold">
            + Tambah Menu
        </button>

        <button
            class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
            Lanjut
        </button>
    </form>
</div>

<script>
function addMenu() {
    const container = document.getElementById('menuContainer');
    const div = document.createElement('div');

    div.className = "flex gap-2 items-center menu-item";
    div.innerHTML = `
        <input type="text" name="menu[]" placeholder="Menu"
            class="w-1/3 border rounded-lg px-3 py-2">

        <input type="number" name="price[]" placeholder="Harga"
            class="w-1/4 border rounded-lg px-3 py-2">

        <input type="number" name="qty[]" placeholder="Qty" value="1" min="1"
            class="w-1/6 border rounded-lg px-3 py-2">

        <button type="button"
            onclick="removeMenu(this)"
            class="text-red-500 font-bold text-xl px-2">
            ×
        </button>
    `;
    container.appendChild(div);
}

function removeMenu(btn) {
    btn.parentElement.remove();
}
</script>

</body>
</html>
