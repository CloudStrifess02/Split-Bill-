<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Split Bill</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

<div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl p-8 space-y-6">

    <h1 class="text-2xl font-bold text-center">💸 Hasil Split Bill</h1>

    {{-- RINGKASAN --}}
    <div class="border rounded-xl p-4 bg-gray-50 space-y-1 text-sm">
        <div class="flex justify-between">
            <span>Subtotal</span>
            <span>Rp {{ number_format($grandSubtotal) }}</span>
        </div>

        @if($discountPercent > 0)
        <div class="flex justify-between text-red-600">
            <span>Diskon {{ $discountPercent }}%</span>
            <span>-</span>
        </div>
        @endif

        @if($discountNominal > 0)
        <div class="flex justify-between text-red-600">
            <span>Diskon Nominal</span>
            <span>- Rp {{ number_format($discountNominal) }}</span>
        </div>
        @endif

        @if($taxOn)
        <div class="flex justify-between">
            <span>Pajak 10%</span>
            <span>+</span>
        </div>
        @endif

        @if($serviceOn)
        <div class="flex justify-between">
            <span>Service 5%</span>
            <span>+</span>
        </div>
        @endif

        <hr>

        <div class="flex justify-between font-bold text-lg">
            <span>Total Akhir</span>
            <span class="text-green-600">
                Rp {{ number_format($grandTotal) }}
            </span>
        </div>
    </div>

    {{-- DETAIL PER ORANG --}}
    <div class="space-y-4">
        @foreach($results as $name => $data)
        <div class="border rounded-xl p-4">
            <h2 class="font-semibold mb-2">{{ $name }}</h2>

            <div class="text-sm space-y-1">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($data['subtotal']) }}</span>
                </div>

                @if($data['discount_percent'] > 0)
                <div class="flex justify-between text-red-600">
                    <span>Diskon Persen</span>
                    <span>- Rp {{ number_format($data['discount_percent']) }}</span>
                </div>
                @endif

                @if($data['discount_nominal'] > 0)
                <div class="flex justify-between text-red-600">
                    <span>Diskon Nominal</span>
                    <span>- Rp {{ number_format($data['discount_nominal']) }}</span>
                </div>
                @endif

                @if($taxOn)
                <div class="flex justify-between">
                    <span>Pajak</span>
                    <span>Rp {{ number_format($data['tax']) }}</span>
                </div>
                @endif

                @if($serviceOn)
                <div class="flex justify-between">
                    <span>Service</span>
                    <span>Rp {{ number_format($data['service']) }}</span>
                </div>
                @endif

                <hr>

                <div class="flex justify-between font-semibold text-green-600">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($data['total']) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- BUTTON --}}
    <a href="/menu"
       class="block text-center bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700">
        Hitung Ulang
    </a>

</div>

</body>
</html>
