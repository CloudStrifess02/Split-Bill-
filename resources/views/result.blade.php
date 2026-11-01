<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Split Bill</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f0e8;
            min-height: 100vh;
            display: flex; align-items: flex-start; justify-content: center;
            padding: 2.5rem 1rem 4rem;
        }
        .wrapper { width: 100%; max-width: 540px; }

        /* HEADER */
        .app-header { text-align: center; margin-bottom: 2rem; }
        .app-logo { display: inline-flex; align-items: center; gap: 0.5rem; background: #fff; border: 1.5px solid #e8e0d0; border-radius: 50px; padding: 0.4rem 1rem; margin-bottom: 1.25rem; }
        .app-logo .dot { width: 10px; height: 10px; border-radius: 50%; background: #2d9e6b; }
        .app-logo span { font-size: 0.8rem; font-weight: 700; color: #3a3228; letter-spacing: 0.04em; }
        .app-title { font-size: 2rem; font-weight: 800; color: #1e1a14; letter-spacing: -0.04em; line-height: 1.15; margin-bottom: 0.4rem; }
        .app-title span { color: #2d9e6b; }
        .app-sub { font-size: 0.9rem; color: #9e8e78; font-weight: 500; }

        /* STEPS */
        .steps { display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; }
        .step { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; font-weight: 700; color: #c8b8a8; }
        .step.active { color: #2d9e6b; }
        .step.done { color: #9e8e78; }
        .step-num { width: 22px; height: 22px; border-radius: 50%; background: #ece6da; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; }
        .step.active .step-num { background: #2d9e6b; color: #fff; }
        .step.done .step-num { background: #9e8e78; color: #fff; }
        .step-line { width: 28px; height: 2px; background: #ece6da; border-radius: 2px; }

        /* GRAND TOTAL HERO */
        .total-hero {
            background: #2d9e6b; border-radius: 20px;
            padding: 2rem; text-align: center; margin-bottom: 1rem;
        }
        .total-hero-label { font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.65); letter-spacing: 0.08em; margin-bottom: 0.4rem; }
        .total-hero-amount { font-size: 2.8rem; font-weight: 800; color: #fff; letter-spacing: -0.04em; line-height: 1; }
        .total-hero-sub { font-size: 0.82rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem; font-weight: 500; }

        /* CARD */
        .card { background: #fff; border: 1.5px solid #e8e0d0; border-radius: 24px; padding: 1.75rem; margin-bottom: 1rem; }
        .section-label { font-size: 0.72rem; font-weight: 700; color: #b8a898; letter-spacing: 0.08em; margin-bottom: 0.85rem; }

        /* SUMMARY ROWS */
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0; font-size: 0.85rem; }
        .summary-row .label { color: #7a6a58; font-weight: 500; }
        .summary-row .value { font-weight: 700; color: #1e1a14; }
        .summary-row.discount .label, .summary-row.discount .value { color: #e07060; }
        .summary-divider { height: 1.5px; background: #f0e8e0; margin: 0.5rem 0; }

        /* PERSON CARDS */
        .person-card { background: #faf7f2; border: 1.5px solid #e8e0d0; border-radius: 16px; overflow: hidden; margin-bottom: 0.75rem; transition: border-color 0.2s; }
        .person-card:hover { border-color: #c8e8d8; }
        .person-card-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.1rem; border-bottom: 1.5px solid #f0e8e0; }
        .person-info { display: flex; align-items: center; gap: 0.65rem; }
        .person-avatar { width: 34px; height: 34px; border-radius: 50%; background: #e8f5ee; border: 1.5px solid #c8e8d8; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; color: #2d9e6b; flex-shrink: 0; }
        .person-name { font-size: 0.95rem; font-weight: 700; color: #1e1a14; }
        .person-total { font-size: 1.05rem; font-weight: 800; color: #2d9e6b; }
        .person-card-body { padding: 0.85rem 1.1rem; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.25rem 0; font-size: 0.8rem; }
        .detail-row .dl { color: #9e8e78; font-weight: 500; }
        .detail-row .dv { font-weight: 700; color: #3a3228; }
        .detail-row.discount .dl, .detail-row.discount .dv { color: #e07060; }
        .detail-divider { height: 1px; background: #ece6da; margin: 0.4rem 0; }

        /* BUTTON */
        .btn-restart {
            display: block; width: 100%; text-align: center;
            background: #fff; border: 1.5px solid #e8e0d0; border-radius: 14px;
            color: #3a3228; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem; font-weight: 700; padding: 0.9rem;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-restart:hover { border-color: #2d9e6b; color: #2d9e6b; background: #f0fdf6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="app-header">
        <div class="app-logo"><div class="dot"></div><span>SPLIT BILL</span></div>
        <h1 class="app-title">All <span>settled!</span></h1>
        <p class="app-sub">Here's the breakdown for everyone</p>
    </div>

    <div class="steps">
        <div class="step done"><div class="step-num">✓</div> Menu</div>
        <div class="step-line"></div>
        <div class="step done"><div class="step-num">✓</div> People</div>
        <div class="step-line"></div>
        <div class="step active"><div class="step-num">3</div> Result</div>
    </div>

    {{-- GRAND TOTAL HERO --}}
    <div class="total-hero">
        <p class="total-hero-label">TOTAL BILL</p>
        <p class="total-hero-amount">Rp {{ number_format($grandTotal) }}</p>
        <p class="total-hero-sub">Split between {{ count($results) }} people</p>
    </div>

    {{-- SUMMARY --}}
    <div class="card">
        <p class="section-label">BILL SUMMARY</p>
        <div class="summary-row">
            <span class="label">Subtotal</span>
            <span class="value">Rp {{ number_format($grandSubtotal) }}</span>
        </div>
        @if($discountPercent > 0)
        <div class="summary-row discount">
            <span class="label">Discount {{ $discountPercent }}%</span>
            <span class="value">−</span>
        </div>
        @endif
        @if($discountNominal > 0)
        <div class="summary-row discount">
            <span class="label">Discount Nominal</span>
            <span class="value">− Rp {{ number_format($discountNominal) }}</span>
        </div>
        @endif
        @if($taxOn)
        <div class="summary-row">
            <span class="label">Tax 10%</span>
            <span class="value">+</span>
        </div>
        @endif
        @if($serviceOn)
        <div class="summary-row">
            <span class="label">Service 5%</span>
            <span class="value">+</span>
        </div>
        @endif
        <div class="summary-divider"></div>
        <div class="summary-row">
            <span class="label" style="font-weight:700; color:#1e1a14;">Total</span>
            <span class="value" style="color:#2d9e6b; font-size:1.05rem;">Rp {{ number_format($grandTotal) }}</span>
        </div>
    </div>

    {{-- PER PERSON --}}
    <div class="card">
        <p class="section-label">PER PERSON</p>
        @php $avatarColors = ['#e8f5ee', '#fef3e8', '#f0e8f5', '#e8f0fe', '#fde8e8']; $i = 0; @endphp
        @foreach($results as $name => $data)
        <div class="person-card">
            <div class="person-card-header">
                <div class="person-info">
                    <div class="person-avatar">{{ strtoupper(substr($name, 0, 2)) }}</div>
                    <span class="person-name">{{ $name }}</span>
                </div>
                <span class="person-total">Rp {{ number_format($data['total']) }}</span>
            </div>
            <div class="person-card-body">
                <div class="detail-row">
                    <span class="dl">Subtotal</span>
                    <span class="dv">Rp {{ number_format($data['subtotal']) }}</span>
                </div>
                @if($data['discount_percent'] > 0)
                <div class="detail-row discount">
                    <span class="dl">Discount %</span>
                    <span class="dv">− Rp {{ number_format($data['discount_percent']) }}</span>
                </div>
                @endif
                @if($data['discount_nominal'] > 0)
                <div class="detail-row discount">
                    <span class="dl">Discount Nominal</span>
                    <span class="dv">− Rp {{ number_format($data['discount_nominal']) }}</span>
                </div>
                @endif
                @if($taxOn)
                <div class="detail-row">
                    <span class="dl">Tax</span>
                    <span class="dv">Rp {{ number_format($data['tax']) }}</span>
                </div>
                @endif
                @if($serviceOn)
                <div class="detail-row">
                    <span class="dl">Service</span>
                    <span class="dv">Rp {{ number_format($data['service']) }}</span>
                </div>
                @endif
            </div>
        </div>
        @php $i++; @endphp
        @endforeach
    </div>

    <a href="/" class="btn-restart">← Start a new split</a>
</div>
</body>
</html>