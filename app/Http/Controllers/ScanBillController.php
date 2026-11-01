<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScanBillController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $apiKey = config('services.gemini.key');
        if (!$apiKey) return response()->json(['error' => 'Fitur scan belum dikonfigurasi.'], 503);

        $imageData = base64_encode(
            file_get_contents($request->file('image')->getRealPath())
        );
        $mimeType = $request->file('image')->getMimeType();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=' . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data'      => $imageData,
                            ]
                        ],
                        [
                            'text' => "This is a receipt. Read carefully and extract all items.
                            CRITICAL RULES:
                            1. COMBINE MODIFIERS/OPTIONS: If a line describes a base item and the next line describes an option (e.g., 'Mie karet'), YOU MUST COMBINE them into a single name (e.g., 'Dandan Original Mie karet').
                            2. DISCOUNTS/VOUCHERS: If an item is immediately followed by a 'VOUCHER', 'DISKON', or a negative amount in parentheses like '(2,000)', YOU MUST SUBTRACT that discount amount from the item's LINE TOTAL before calculating unit price.
                            3. UNIT PRICE: 'price' must always be the price PER SINGLE PIECE (unit price), NOT the line total.
                               - To get unit price: (line total - voucher) / qty.
                               - Example: qty=2, line total=8400, no voucher → unit price = 4200.
                               - Example: qty=1, line total=15900, voucher=2000 → unit price = 13900.
                            4. 'qty' is the number of pieces purchased. If not shown, default to 1.
                            5. Skip summary lines (subtotal, total, tax, PPN, ANDA HEMAT, HARGA JUAL, etc).
                            6. Price must be a plain integer without currency symbols or dots.

                            Return ONLY a valid JSON array, no explanation, no markdown, just raw JSON.
                            Format: [{\"name\": \"Menu Name\", \"price\": 4200, \"qty\": 2}]"
                        ]
                    ]
                ]
            ]
        ]);

        if (!$response->successful()) return response()->json(['error' => 'Layanan scan sedang bermasalah. Coba lagi nanti.'], 502);
        $text = $response->json('candidates.0.content.parts.0.text', '');
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        $items = json_decode($text, true);

        if (!is_array($items) || !$items) {
            return response()->json([
                'error' => 'Gagal membaca struk',
            ], 422);
        }

        $items = array_values(array_filter($items, fn ($item) => is_array($item)
            && isset($item['name'], $item['price']) && is_string($item['name'])
            && is_numeric($item['price']) && (float) $item['price'] >= 0));
        if (!$items) return response()->json(['error' => 'Tidak ada item struk yang terbaca.'], 422);

        return response()->json(['items' => $items]);
    }
}
