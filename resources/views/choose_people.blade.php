<!DOCTYPE html>
<html lang="en">
<head>
    <title>Siapa Makan Apa?</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex; align-items: flex-start; justify-content: center; padding: 2.5rem 1rem 4rem; }
        .wrapper { width: 100%; max-width: 560px; }
        .app-header { text-align: center; margin-bottom: 2rem; }
        .app-logo { display: inline-flex; align-items: center; gap: 0.5rem; background: #fff; border: 1.5px solid #e8e0d0; border-radius: 50px; padding: 0.4rem 1rem; margin-bottom: 1.25rem; }
        .app-logo .dot { width: 10px; height: 10px; border-radius: 50%; background: #2d9e6b; }
        .app-logo span { font-size: 0.8rem; font-weight: 700; color: #3a3228; letter-spacing: 0.04em; }
        .app-title { font-size: 2rem; font-weight: 800; color: #1e1a14; letter-spacing: -0.04em; line-height: 1.15; margin-bottom: 0.4rem; }
        .app-title span { color: #2d9e6b; }
        .app-sub { font-size: 0.9rem; color: #9e8e78; font-weight: 500; }
        .steps { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; }
        .step { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; font-weight: 700; color: #c8b8a8; }
        .step.active { color: #2d9e6b; }
        .step.done { color: #9e8e78; }
        .step-num { width: 22px; height: 22px; border-radius: 50%; background: #ece6da; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; }
        .step.active .step-num { background: #2d9e6b; color: #fff; }
        .step.done .step-num { background: #9e8e78; color: #fff; }
        .step-line { width: 28px; height: 2px; background: #ece6da; border-radius: 2px; }
        .card { background: #fff; border: 1.5px solid #e8e0d0; border-radius: 24px; padding: 1.75rem; margin-bottom: 1rem; }
        .section-label { font-size: 0.72rem; font-weight: 700; color: #b8a898; letter-spacing: 0.08em; margin-bottom: 0.85rem; }
        #peopleContainer { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem; }
        .person-row { display: flex; align-items: center; gap: 0.6rem; }
        .person-avatar { width: 34px; height: 34px; border-radius: 50%; background: #e8f5ee; border: 1.5px solid #c8e8d8; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; color: #2d9e6b; flex-shrink: 0; }
        .person-input { flex: 1; background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 10px; color: #1e1a14; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; font-weight: 500; padding: 0.6rem 0.85rem; outline: none; transition: border-color 0.2s; }
        .person-input:focus { border-color: #2d9e6b; background: #fff; }
        .person-input::placeholder { color: #c8b8a8; }
        .btn-add-person { background: none; border: none; color: #2d9e6b; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 700; cursor: pointer; padding: 0.4rem 0; transition: opacity 0.2s; }
        .btn-add-person:hover { opacity: 0.7; }
        .menu-card { background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 14px; padding: 1rem 1.1rem; margin-bottom: 0.6rem; }
        .menu-card-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.75rem; }
        .menu-name { font-size: 0.95rem; font-weight: 700; color: #1e1a14; }
        .menu-meta { font-size: 0.75rem; font-weight: 600; color: #9e8e78; }
        .checkbox-container { display: flex; flex-direction: column; gap: 0.4rem; }
        .person-check-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.4rem 0.5rem; border-radius: 8px; transition: background 0.15s; }
        .person-check-row:hover { background: rgba(45,158,107,0.05); }
        .person-check-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: #2d9e6b; cursor: pointer; flex-shrink: 0; }
        .person-check-row .pname { flex: 1; font-size: 0.85rem; font-weight: 600; color: #3a3228; }
        .qty-input { width: 60px; background: #fff; border: 1.5px solid #e0d8cc; border-radius: 8px; color: #1e1a14; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; padding: 0.35rem 0.5rem; text-align: center; outline: none; transition: border-color 0.2s; }
        .qty-input:focus { border-color: #2d9e6b; }
        .qty-error { font-size: 0.75rem; font-weight: 600; color: #e07060; margin-top: 0.4rem; display: none; }
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .option-pill { display: flex; align-items: center; gap: 0.6rem; background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 12px; padding: 0.7rem 0.9rem; cursor: pointer; transition: all 0.2s; }
        .option-pill:hover { border-color: #2d9e6b; background: #f0fdf6; }
        .option-pill input[type="checkbox"] { accent-color: #2d9e6b; width: 15px; height: 15px; flex-shrink: 0; }
        .option-pill label { font-size: 0.85rem; font-weight: 600; color: #3a3228; cursor: pointer; }
        .discount-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .discount-input { background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 10px; color: #1e1a14; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem; font-weight: 500; padding: 0.65rem 0.85rem; outline: none; width: 100%; transition: border-color 0.2s; }
        .discount-input:focus { border-color: #2d9e6b; background: #fff; }
        .discount-input::placeholder { color: #c8b8a8; }
        .discount-note { font-size: 0.72rem; font-weight: 600; color: #b8a898; text-align: center; margin-top: 0.5rem; }
        .preview-box { background: #f0fdf6; border: 1.5px solid #c8e8d8; border-radius: 16px; padding: 1.1rem 1.25rem; }
        .preview-title { font-size: 0.72rem; font-weight: 700; color: #6a9e7a; letter-spacing: 0.08em; margin-bottom: 0.75rem; }
        #previewResult { display: flex; flex-direction: column; gap: 0.4rem; }
        .preview-person { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.65rem; background: #fff; border-radius: 10px; border: 1px solid #d8f0e4; }
        .preview-person .pn { font-size: 0.85rem; font-weight: 600; color: #3a3228; }
        .preview-person .amt { font-size: 0.9rem; font-weight: 800; color: #2d9e6b; }
        .preview-empty { font-size: 0.8rem; font-weight: 600; color: #a8c8b8; text-align: center; padding: 0.5rem 0; }
        .btn-submit { width: 100%; background: #2d9e6b; border: none; border-radius: 14px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 700; padding: 0.95rem; cursor: pointer; transition: all 0.2s; margin-top: 1rem; }
        .btn-submit:hover { background: #238a5a; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="app-header">
        <div class="app-logo"><div class="dot"></div><span>SPLIT BILL</span></div>
        <h1 class="app-title">Who ate <span>what?</span></h1>
        <p class="app-sub">Assign people to each menu item</p>
    </div>

    <div class="steps">
        <div class="step done"><div class="step-num">✓</div> Menu</div>
        <div class="step-line"></div>
        <div class="step active"><div class="step-num">2</div> People</div>
        <div class="step-line"></div>
        <div class="step"><div class="step-num">3</div> Result</div>
    </div>

    <form method="POST" action="/calculate" id="mainForm" onsubmit="return validateForm()">
        @csrf

        {{-- Pass menu/price/qty from controller as hidden inputs --}}
        @foreach($menu as $i => $name)
            <input type="hidden" name="menu[]" value="{{ $name }}">
            <input type="hidden" name="price[]" value="{{ $price[$i] }}">
            <input type="hidden" name="qty[]" value="{{ $qty[$i] }}">
        @endforeach

        {{-- PEOPLE --}}
        <div class="card">
            <p class="section-label">PEOPLE</p>
            <div id="peopleContainer">
                <div class="person-row">
                    <div class="person-avatar">01</div>
                    <input type="text" class="person-input" placeholder="Nama 1">
                </div>
                <div class="person-row">
                    <div class="person-avatar">02</div>
                    <input type="text" class="person-input" placeholder="Nama 2">
                </div>
            </div>
            <button type="button" class="btn-add-person" onclick="addPerson()">+ Add person</button>
        </div>

        {{-- MENU ITEMS --}}
        <div class="card">
            <p class="section-label">MENU ITEMS</p>
            @foreach($menu as $i => $name)
            <div class="menu-card">
                <div class="menu-card-header">
                    <span class="menu-name">{{ $name }}</span>
                    <span class="menu-meta">Rp {{ number_format($price[$i]) }} · Qty {{ $qty[$i] }}</span>
                </div>
                <div class="checkbox-container" data-menu="{{ $i }}" data-price="{{ $price[$i] }}" data-total-qty="{{ $qty[$i] }}"></div>
                <p id="qty-error-{{ $i }}" class="qty-error">✗ Quantity doesn't match</p>
            </div>
            @endforeach
        </div>

        {{-- TAX & SERVICE --}}
        <div class="card">
            <p class="section-label">TAX & SERVICE</p>
            <div class="options-grid">
                <div class="option-pill">
                    <input type="checkbox" id="tax">
                    <label for="tax">Tax 10%</label>
                </div>
                <div class="option-pill">
                    <input type="checkbox" id="service">
                    <label for="service">Service 5%</label>
                </div>
            </div>
        </div>

        {{-- DISCOUNT --}}
        <div class="card">
            <p class="section-label">DISCOUNT (OPTIONAL)</p>
            <div class="discount-grid">
                <input type="number" id="discountPercent" class="discount-input" placeholder="Percent (%)" min="0" max="100">
                <input type="number" id="discountAmount" class="discount-input" placeholder="Nominal (Rp)" min="0">
            </div>
            <p class="discount-note">Fill one only</p>
        </div>

        {{-- PREVIEW --}}
        <div class="card">
            <div class="preview-box">
                <p class="preview-title">LIVE PREVIEW</p>
                <div id="previewResult">
                    <p class="preview-empty">Select items above to see totals</p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">Calculate Split Bill →</button>
    </form>
</div>

<script>
function addPerson() {
    const container = document.getElementById('peopleContainer');
    const num = container.children.length + 1;
    const row = document.createElement('div');
    row.className = 'person-row';
    row.innerHTML = `<div class="person-avatar">${String(num).padStart(2,'0')}</div><input type="text" class="person-input" placeholder="Nama ${num}">`;
    container.appendChild(row);
    updateCheckboxes();
}

function getPeople() {
    return [...document.querySelectorAll('.person-input')].map(i => i.value.trim()).filter(Boolean);
}

function updateCheckboxes() {
    const people = getPeople();
    document.querySelectorAll('.checkbox-container').forEach(box => {
        const totalQty = +box.dataset.totalQty;
            const saved = {};
            box.querySelectorAll('.person-check-row').forEach(row => {
                const name = row.querySelector('.pname')?.textContent;
                saved[name] = { checked: row.querySelector('input[type=checkbox]')?.checked, qty: row.querySelector('.qty-input')?.value };
            });
            box.innerHTML = '';
        people.forEach(p => {
            const row = document.createElement('div');
            row.className = 'person-check-row';
            let qtyHtml = totalQty > 1 ? `<input type="number" min="0" value="0" class="qty-input" data-person="${p}">` : '';
                row.innerHTML = `<input type="checkbox"><span class="pname"></span>${qtyHtml}`;
                row.querySelector('.pname').textContent = p;
                const cb = row.querySelector('input[type=checkbox]'); cb.dataset.person = p; cb.checked = !!saved[p]?.checked;
                const quantity = row.querySelector('.qty-input');
                if (quantity) { quantity.dataset.person = p; quantity.value = saved[p]?.qty || 0; }
            box.appendChild(row);
        });
    });
    calculatePreview();
}

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
        let sumQty = 0;
        if (totalQty === 1) checked.forEach(cb => { subtotal[cb.dataset.person] += price; sumQty++; });
        else box.querySelectorAll('.qty-input').forEach(q => {
            const amount = +q.value || 0; sumQty += amount;
            if (amount) subtotal[q.dataset.person] += price * amount / totalQty;
        });
        if (sumQty !== totalQty) { errorEl.style.display = 'block'; error = true; }
        else errorEl.style.display = 'none';
    });
    if (error) { renderPreview({}); return; }
    applyDiscountTax(subtotal);
}

function applyDiscountTax(subtotal) {
    const percent = +document.getElementById('discountPercent').value || 0;
    const nominal = +document.getElementById('discountAmount').value || 0;
    const totalAll = Object.values(subtotal).reduce((a,b)=>a+b,0);
    if (totalAll <= 0) { renderPreview(subtotal); return; }
    let discountTotal = 0;
    if (percent > 0) discountTotal = totalAll * percent / 100;
    else if (nominal > 0) discountTotal = nominal;
    discountTotal = Math.min(discountTotal, totalAll);
    renderPreview(subtotal, discountTotal, document.getElementById('tax').checked, document.getElementById('service').checked);
}

function renderPreview(subtotal, discountTotal=0, taxOn=false, serviceOn=false) {
    const box = document.getElementById('previewResult');
    if (!Object.keys(subtotal).length) { box.innerHTML = '<p class="preview-empty">Select items above to see totals</p>'; return; }
    const totalAll = Object.values(subtotal).reduce((a,b)=>a+b,0);
    box.innerHTML = '';
    Object.entries(subtotal).forEach(([name, val]) => {
        const ratio = totalAll > 0 ? val / totalAll : 0;
        const disc = discountTotal * ratio;
        const after = val - disc;
        const tax = taxOn ? after * 0.10 : 0;
        const svc = serviceOn ? after * 0.05 : 0;
        const final = after + tax + svc;
        const div = document.createElement('div');
        div.className = 'preview-person';
        div.innerHTML = `<span class="pn">${name}</span><span class="amt">Rp ${Math.round(final).toLocaleString('id-ID')}</span>`;
        box.appendChild(div);
    });
}

document.addEventListener('input', e => {
    if (e.target.classList.contains('person-input')) updateCheckboxes();
    else calculatePreview();
});
document.addEventListener('change', calculatePreview);
updateCheckboxes();

function validateForm() {
    calculatePreview();

    // Block if qty errors exist
    const hasError = [...document.querySelectorAll('.qty-error')].some(el => el.style.display === 'block');
    if (hasError) return false;

    const people = getPeople();
    if (!people.length) { alert('Add at least one person!'); return false; }

    const discPercent = +document.getElementById('discountPercent').value || 0;
    const discNominal = +document.getElementById('discountAmount').value || 0;
    if (discPercent > 0 && discNominal > 0) { alert('Fill one discount only!'); return false; }

    const form = document.getElementById('mainForm');

    // Clear previously injected inputs
    document.querySelectorAll('.dyn-input').forEach(el => el.remove());

    function inject(name, value) {
        const el = document.createElement('input');
        el.type = 'hidden';
        el.name = name;
        el.value = value;
        el.className = 'dyn-input';
        form.appendChild(el);
    }

    // 1. People names
    people.forEach(name => inject('people[]', name));

    // 2. Tax & service
    if (document.getElementById('tax').checked) inject('tax', '1');
    if (document.getElementById('service').checked) inject('service', '1');

    // 3. Discount
    if (discPercent > 0) inject('discount_percent', discPercent);
    if (discNominal > 0) inject('discount_nominal', discNominal);

    // 4. Eaten — who checked what
    document.querySelectorAll('.checkbox-container').forEach(box => {
        const menuIndex = box.dataset.menu;
        box.querySelectorAll('.person-check-row').forEach(row => {
            const cb = row.querySelector('input[type=checkbox]');
            const person = row.querySelector('.pname').textContent;
            const q = row.querySelector('.qty-input');
            inject(`eaten_qty[${menuIndex}][${person}]`, q ? (q.value || 0) : (cb.checked ? 1 : 0));
        });
    });

    return true;
}
</script>
</body>
</html>
