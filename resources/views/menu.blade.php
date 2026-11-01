<!DOCTYPE html>
<html lang="en">
<head>
    <title>Split Bill</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f0e8;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1rem 4rem;
        }
        .wrapper { width: 100%; max-width: 540px; }

        /* HEADER */
        .app-header { text-align: center; margin-bottom: 2rem; }
        .app-logo {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #fff; border: 1.5px solid #e8e0d0;
            border-radius: 50px; padding: 0.4rem 1rem; margin-bottom: 1.25rem;
        }
        .app-logo .dot { width: 10px; height: 10px; border-radius: 50%; background: #2d9e6b; }
        .app-logo span { font-size: 0.8rem; font-weight: 700; color: #3a3228; letter-spacing: 0.04em; }
        .app-title { font-size: 2.2rem; font-weight: 800; color: #1e1a14; letter-spacing: -0.04em; line-height: 1.15; margin-bottom: 0.4rem; }
        .app-title span { color: #2d9e6b; }
        .app-sub { font-size: 0.9rem; color: #9e8e78; font-weight: 500; }

        /* STEPS */
        .steps { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; }
        .step { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; font-weight: 700; color: #c8b8a8; }
        .step.active { color: #2d9e6b; }
        .step-num { width: 22px; height: 22px; border-radius: 50%; background: #ece6da; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; }
        .step.active .step-num { background: #2d9e6b; color: #fff; }
        .step-line { width: 28px; height: 2px; background: #ece6da; border-radius: 2px; }

        /* SCAN CARD */
        .scan-card {
            background: #edfaf3; border: 2px dashed #a8dfc4;
            border-radius: 20px; padding: 2.5rem 2rem;
            text-align: center; margin-bottom: 1rem;
            transition: border-color 0.2s, background 0.2s;
        }
        .scan-card:hover { border-color: #2d9e6b; background: #e4f8ee; }
        .scan-icon { width: 68px; height: 68px; background: #c8f0dc; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.1rem; font-size: 2rem; }
        .scan-title { font-size: 1.2rem; font-weight: 800; color: #1e1a14; margin-bottom: 0.4rem; }
        .scan-sub { font-size: 0.85rem; color: #6a9e7a; line-height: 1.6; margin-bottom: 1.25rem; }
        .btn-scan {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #2d9e6b; color: #fff; border: none; border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; font-weight: 700;
            padding: 0.75rem 1.75rem; cursor: pointer; transition: all 0.2s;
        }
        .btn-scan:hover { background: #238a5a; transform: translateY(-1px); }
        #scanStatus { margin-top: 1rem; font-size: 0.82rem; font-weight: 600; display: none; }
        .scan-actions { display:flex; justify-content:center; gap:.6rem; flex-wrap:wrap; }
        .btn-upload { background:#fff; color:#2d9e6b; border:1.5px solid #a8dfc4; border-radius:12px; padding:.7rem 1rem; font:600 .85rem 'Plus Jakarta Sans',sans-serif; cursor:pointer; }
        .camera-modal { position:fixed; inset:0; z-index:10; background:rgba(20,25,22,.88); display:none; align-items:center; justify-content:center; padding:1rem; }
        .camera-modal.open { display:flex; }
        .camera-panel { width:min(100%,560px); background:#fff; border-radius:20px; padding:1rem; text-align:center; }
        .camera-panel video { width:100%; max-height:65vh; object-fit:cover; background:#111; border-radius:14px; }
        .camera-controls { display:flex; justify-content:center; gap:.6rem; margin-top:.8rem; }

        /* DIVIDER */
        .divider { display: flex; align-items: center; gap: 0.75rem; margin: 1.25rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1.5px; background: #e0d8cc; }
        .divider span { font-size: 0.72rem; font-weight: 700; color: #b8a898; letter-spacing: 0.08em; }

        /* CARD */
        .card { background: #fff; border: 1.5px solid #e8e0d0; border-radius: 24px; padding: 2rem; }
        .section-label { font-size: 0.72rem; font-weight: 700; color: #b8a898; letter-spacing: 0.08em; margin-bottom: 0.75rem; }

        /* MENU ROWS */
        .col-heads { display: grid; grid-template-columns: 1fr 100px 56px 32px; gap: 0.5rem; padding-bottom: 0.4rem; font-size: 0.68rem; font-weight: 700; color: #c8b8a8; letter-spacing: 0.06em; }
        #menuContainer { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem; }
        .menu-item { display: grid; grid-template-columns: 1fr 100px 56px 32px; gap: 0.5rem; align-items: center; }
        .menu-item input {
            background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 10px;
            color: #1e1a14; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 500; padding: 0.6rem 0.75rem;
            outline: none; transition: border-color 0.2s, background 0.2s; width: 100%;
        }
        .menu-item input:focus { border-color: #2d9e6b; background: #fff; }
        .menu-item input::placeholder { color: #c8b8a8; }
        .btn-remove { background: none; border: none; color: #d4bfaf; font-size: 1.2rem; cursor: pointer; transition: color 0.2s; display: flex; align-items: center; justify-content: center; }
        .btn-remove:hover { color: #e07060; }

        .btn-add {
            width: 100%; background: none; border: 1.5px dashed #d4c8b8; border-radius: 12px;
            color: #9e8e78; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 600; padding: 0.65rem; cursor: pointer; transition: all 0.2s; margin-bottom: 1.25rem;
        }
        .btn-add:hover { border-color: #2d9e6b; color: #2d9e6b; background: #f0fdf6; }
        .btn-submit {
            width: 100%; background: #2d9e6b; border: none; border-radius: 14px;
            color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem;
            font-weight: 700; padding: 0.95rem; cursor: pointer; transition: all 0.2s;
        }
        .btn-submit:hover { background: #238a5a; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="app-header">
        <div class="app-logo"><div class="dot"></div><span>SPLIT BILL</span></div>
        <h1 class="app-title">Split the <span>bill,</span><br>not the friendship.</h1>
        <p class="app-sub">Add your items and we'll do the math</p>
    </div>

    <div class="steps">
        <div class="step active"><div class="step-num">1</div> Menu</div>
        <div class="step-line"></div>
        <div class="step"><div class="step-num">2</div> People</div>
        <div class="step-line"></div>
        <div class="step"><div class="step-num">3</div> Result</div>
    </div>

    <input type="file" id="billImage" accept="image/*" style="display:none">
    <input type="file" id="cameraFallback" accept="image/*" capture="environment" style="display:none">
    <div class="scan-card">
        <div class="scan-icon">📷</div>
        <p class="scan-title">Scan your receipt</p>
        <p class="scan-sub">Ambil foto struk dengan kamera atau pilih gambar dari perangkat.</p>
        <div class="scan-actions">
            <button type="button" class="btn-scan" onclick="openCamera()">📷 Foto sekarang</button>
            <button type="button" class="btn-upload" onclick="document.getElementById('billImage').click()">Pilih foto</button>
        </div>
        <div id="scanStatus"></div>
    </div>

    <div class="camera-modal" id="cameraModal" role="dialog" aria-modal="true" aria-label="Kamera struk">
        <div class="camera-panel">
            <video id="cameraPreview" autoplay playsinline></video>
            <div class="camera-controls">
                <button type="button" class="btn-scan" onclick="takePhoto()">Ambil foto</button>
                <button type="button" class="btn-upload" onclick="closeCamera()">Tutup</button>
            </div>
        </div>
    </div>

    <div class="divider"><span>OR ADD MANUALLY</span></div>

    <div class="card">
        <form method="POST" action="/menu" id="menuForm">
            @csrf
            <p class="section-label">MENU ITEMS</p>
            <div class="col-heads">
                <span>ITEM NAME</span><span>PRICE (RP)</span><span>QTY</span><span></span>
            </div>
            <div id="menuContainer">
                <div class="menu-item">
                    <input type="text" name="menu[]" placeholder="e.g. Nasi Goreng">
                    <input type="number" name="price[]" placeholder="25000">
                    <input type="number" name="qty[]" value="1" min="1">
                    <button type="button" onclick="removeMenu(this)" class="btn-remove">×</button>
                </div>
            </div>
            <button type="button" onclick="addMenu()" class="btn-add">+ Add another item</button>
            <button type="submit" class="btn-submit">Continue to Split →</button>
        </form>
    </div>
</div>

<script>
function addMenu() {
    const div = document.createElement('div');
    div.className = 'menu-item';
    div.innerHTML = `
        <input type="text" name="menu[]" placeholder="e.g. Nasi Goreng">
        <input type="number" name="price[]" placeholder="25000">
        <input type="number" name="qty[]" value="1" min="1">
        <button type="button" onclick="removeMenu(this)" class="btn-remove">×</button>
    `;
    document.getElementById('menuContainer').appendChild(div);
}
function removeMenu(btn) {
    if (document.querySelectorAll('.menu-item').length > 1) btn.parentElement.remove();
}
let cameraStream = null;
async function openCamera() {
    const modal = document.getElementById('cameraModal');
    try {
        if (!navigator.mediaDevices?.getUserMedia) throw new Error('unsupported');
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false });
        document.getElementById('cameraPreview').srcObject = cameraStream;
        modal.classList.add('open');
    } catch (error) {
        // Browser/device lacks direct camera access; use the native camera picker when available.
        document.getElementById('cameraFallback').click();
    }
}
function closeCamera() {
    cameraStream?.getTracks().forEach(track => track.stop()); cameraStream = null;
    document.getElementById('cameraPreview').srcObject = null;
    document.getElementById('cameraModal').classList.remove('open');
}
function takePhoto() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.createElement('canvas'); canvas.width = video.videoWidth; canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.toBlob(blob => { if (blob) { closeCamera(); scanReceipt(new File([blob], 'receipt.jpg', { type: 'image/jpeg' })); } }, 'image/jpeg', .9);
}
async function scanReceipt(file) {
    if (!file) return;
    const status = document.getElementById('scanStatus');
    status.style.display = 'block';
    status.style.color = '#2d9e6b';
    status.textContent = '⏳ Reading your receipt...';
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', '{{ csrf_token() }}');
    try {
        const res = await fetch('/scan-bill', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.error) {
            status.style.color = '#e07060';
            status.textContent = '✗ ' + data.error;
            return;
        }
        document.getElementById('menuContainer').innerHTML = '';
        data.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'menu-item';
            const name = document.createElement('input'); name.name = 'menu[]'; name.value = item.name; name.maxLength = 120; name.required = true;
            const price = document.createElement('input'); price.type = 'number'; price.name = 'price[]'; price.value = item.price; price.min = '0'; price.required = true;
            const qty = document.createElement('input'); qty.type = 'number'; qty.name = 'qty[]'; qty.value = item.qty || 1; qty.min = '1'; qty.required = true;
            const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'btn-remove'; remove.textContent = '×'; remove.onclick = () => removeMenu(remove);
            div.append(name, price, qty, remove);
            document.getElementById('menuContainer').appendChild(div);
        });
        status.style.color = '#2d9e6b';
        status.textContent = '✓ Receipt scanned! Review and edit if needed.';
        if (!res.ok) throw new Error(data.error || 'scan failed');
    } catch (err) {
        status.style.color = '#e07060';
        status.textContent = '✗ Foto gagal diproses. Silakan coba lagi atau pilih foto lain.';
    }
}
document.getElementById('billImage').addEventListener('change', function () { scanReceipt(this.files[0]); });
document.getElementById('cameraFallback').addEventListener('change', function () { scanReceipt(this.files[0]); });
</script>
</body>
</html>
