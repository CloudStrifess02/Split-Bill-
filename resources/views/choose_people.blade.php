<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Siapa Makan Apa</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

<div class="bg-white max-w-xl w-full rounded-2xl shadow-xl p-8 space-y-6">
    <h1 class="text-2xl font-bold text-center">Siapa Makan Apa?</h1>

    <form method="POST" action="/calculate" onsubmit="return validateForm()" class="space-y-6">
        @csrf

        <!-- ================= ORANG ================= -->
        <div>
            <label class="font-semibold block mb-2">Nama Orang</label>
            <div id="peopleContainer" class="space-y-2">
                <input type="text" class="person-input border rounded px-3 py-2 w-full" value="Samuel">
                <input type="text" class="person-input border rounded px-3 py-2 w-full" value="Sharon">
            </div>
            <button type="button" onclick="addPerson()" class="mt-2 text-blue-600 font-semibold">
                + Tambah Orang
            </button>
        </div>

        <!-- ================= MENU ================= -->
        @foreach($menu as $i => $name)
        <div class="border rounded-xl p-4 space-y-2">
            <h2 class="font-semibold">
                {{ $name }}
                <span class="text-sm text-gray-500">
                    (Rp {{ number_format($price[$i]) }}, Qty {{ $qty[$i] }})
                </span>
            </h2>

            <div class="checkbox-container space-y-2"
                 data-menu="{{ $i }}"
                 data-price="{{ $price[$i] }}"
                 data-total-qty="{{ $qty[$i] }}">
            </div>

            <p id="qty-error-{{ $i }}" class="text-red-500 text-sm hidden">
                Kuantitas tidak sesuai
            </p>
        </div>
        @endforeach

        <!-- ================= PAJAK ================= -->
        <div class="border rounded-xl p-4 space-y-2">
            <label class="flex gap-2">
                <input type="checkbox" id="tax"> Pajak 10%
            </label>
            <label class="flex gap-2">
                <input type="checkbox" id="service"> Service 5%
            </label>
        </div>

        <!-- ================= DISKON ================= -->
        <div class="border rounded-xl p-4 space-y-3">
            <h2 class="font-semibold text-center">🎁 Diskon</h2>

            <input type="number" id="discountPercent"
                   placeholder="Diskon persen (%)"
                   min="0" max="100"
                   class="border rounded px-2 py-1 w-full">

            <input type="number" id="discountAmount"
                   placeholder="Diskon nominal (Rp)"
                   min="0"
                   class="border rounded px-2 py-1 w-full">

            <p class="text-xs text-gray-500 text-center">
                Isi salah satu saja
            </p>
        </div>

        <!-- ================= PREVIEW ================= -->
        <div class="border rounded-xl p-4 bg-gray-50">
            <h2 class="font-semibold text-center mb-3">💸 Preview Total</h2>
            <div id="previewResult" class="space-y-4 text-sm"></div>
        </div>

        <button class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold">
            Hitung Split Bill
        </button>
    </form>
</div>

<script>
/* ================= PEOPLE ================= */
function addPerson() {
    const i = document.createElement('input');
    i.className = 'person-input border rounded px-3 py-2 w-full';
    i.placeholder = 'Nama orang';
    document.getElementById('peopleContainer').appendChild(i);
    updateCheckboxes();
}

function getPeople() {
    return [...document.querySelectorAll('.person-input')]
        .map(i => i.value.trim())
        .filter(Boolean);
}

/* ================= CHECKBOX ================= */
function updateCheckboxes() {
    const people = getPeople();

    document.querySelectorAll('.checkbox-container').forEach(box => {
        const totalQty = +box.dataset.totalQty;
        box.innerHTML = '';

        people.forEach(p => {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';

            let qty = '';
            if (totalQty > 1) {
                qty = `<input type="number" min="0" value="0"
                        class="qty-input w-16 border rounded px-2 py-1"
                        data-person="${p}">`;
            }

            row.innerHTML = `
                <input type="checkbox" data-person="${p}">
                <span class="w-24">${p}</span>
                ${qty}
            `;
            box.appendChild(row);
        });
    });

    calculatePreview();
}

/* ================= HITUNG ================= */
function calculatePreview() {
    const people = getPeople();
    let subtotal = {};
    people.forEach(p => subtotal[p] = 0);

    let error = false;

    document.querySelectorAll('.checkbox-container').forEach(box => {
        const price = +box.dataset.price;
        const totalQty = +box.dataset.totalQty;
        const errorEl = document.getElementById(`qty-error-${box.dataset.menu}`);

        const checked = box.querySelectorAll('input[type="checkbox"]:checked');
        if (!checked.length) return;

        if (totalQty === 1) {
            checked.forEach(cb => {
                subtotal[cb.dataset.person] += price / checked.length;
            });
            errorEl.classList.add('hidden');
            return;
        }

        let sumQty = 0;
        box.querySelectorAll('.qty-input').forEach(q => sumQty += +q.value || 0);

        if (sumQty !== totalQty) {
            errorEl.classList.remove('hidden');
            error = true;
            return;
        } else errorEl.classList.add('hidden');

        checked.forEach(cb => {
            const q = box.querySelector(`.qty-input[data-person="${cb.dataset.person}"]`);
            subtotal[cb.dataset.person] += price * ((+q.value || 0) / totalQty);
        });
    });

    if (error) {
        renderPreview({});
        return;
    }

    applyDiscountTax(subtotal);
}

/* ================= DISKON + PAJAK ================= */
function applyDiscountTax(subtotal) {
    const percent = +discountPercent.value || 0;
    const nominal = +discountAmount.value || 0;

    if (percent > 0 && nominal > 0) {
        alert('Isi salah satu diskon saja');
        return;
    }

    const totalAll = Object.values(subtotal).reduce((a,b)=>a+b,0);
    if (totalAll <= 0) {
        renderPreview(subtotal);
        return;
    }

    let discountTotal = 0;
    if (percent > 0) discountTotal = totalAll * percent / 100;
    else if (nominal > 0) discountTotal = nominal;

    discountTotal = Math.min(discountTotal, totalAll);

    renderPreview(subtotal, discountTotal, tax.checked, service.checked);
}

/* ================= RENDER ================= */
function renderPreview(subtotal, discountTotal = 0, taxOn = false, serviceOn = false) {
    const box = document.getElementById('previewResult');
    box.innerHTML = '';

    if (!Object.keys(subtotal).length) {
        box.innerHTML = `<p class="text-center text-gray-400">Periksa kuantitas</p>`;
        return;
    }

    const totalAll = Object.values(subtotal).reduce((a,b)=>a+b,0);

    Object.entries(subtotal).forEach(([name, val]) => {
        const ratio = val / totalAll;
        const disc = discountTotal * ratio;
        const afterDisc = val - disc;
        const tax = taxOn ? afterDisc * 0.10 : 0;
        const service = serviceOn ? afterDisc * 0.05 : 0;
        const final = afterDisc + tax + service;

        box.innerHTML += `
        <div class="border rounded-lg p-3 space-y-1">
            <div class="flex justify-between"><span>${name}</span><b>Rp ${Math.round(final).toLocaleString()}</b></div>
            <div class="text-xs text-gray-500">
                Subtotal: Rp ${Math.round(val).toLocaleString()}<br>
                Diskon: -Rp ${Math.round(disc).toLocaleString()}<br>
                Pajak: Rp ${Math.round(tax).toLocaleString()}<br>
                Service: Rp ${Math.round(service).toLocaleString()}
            </div>
        </div>`;
    });
}

/* ================= EVENT ================= */
document.addEventListener('input', e => {
    if (e.target.classList.contains('person-input')) updateCheckboxes();
    else calculatePreview();
});
document.addEventListener('change', calculatePreview);

updateCheckboxes();

function validateForm() {
    calculatePreview();
    return !document.querySelector('.text-red-500:not(.hidden)');
}
</script>

</body>
</html>
