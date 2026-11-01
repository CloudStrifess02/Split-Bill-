<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SplitBillController extends Controller
{
    public function showMenuForm()
    {
        return view('menu');
    }

    public function submitMenu(Request $request)
    {
        $data = $request->validate([
            'menu' => 'required|array|min:1|max:100',
            'menu.*' => 'required|string|max:120',
            'price' => 'required|array|min:1|max:100',
            'price.*' => 'required|numeric|min:0|max:100000000',
            'qty' => 'required|array|min:1|max:100',
            'qty.*' => 'required|integer|min:1|max:1000',
        ]);

        if (count($data['menu']) !== count($data['price']) || count($data['menu']) !== count($data['qty'])) {
            return back()->withErrors(['menu' => 'Data item, harga, dan jumlah tidak cocok.'])->withInput();
        }

        $totalPrice = [];
        foreach ($data['price'] as $i => $price) {
            $totalPrice[$i] = (float) $price * (int) $data['qty'][$i];
        }

        return view('choose_people', ['menu' => $data['menu'], 'price' => $totalPrice, 'qty' => $data['qty']]);
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'menu' => 'required|array|min:1|max:100', 'menu.*' => 'required|string|max:120',
            'price' => 'required|array|min:1|max:100', 'price.*' => 'required|numeric|min:0|max:100000000000',
            'qty' => 'required|array|min:1|max:100', 'qty.*' => 'required|integer|min:1|max:1000',
            'people' => 'required|array|min:1|max:50', 'people.*' => 'required|string|max:60',
            'eaten_qty' => 'nullable|array', 'eaten_qty.*' => 'nullable|array',
            'eaten_qty.*.*' => 'nullable|integer|min:0|max:1000',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_nominal' => 'nullable|numeric|min:0|max:100000000000',
            'tax' => 'nullable|boolean', 'service' => 'nullable|boolean',
        ]);

        if (count($data['menu']) !== count($data['price']) || count($data['menu']) !== count($data['qty'])) {
            return back()->withErrors(['menu' => 'Data item tidak cocok.'])->withInput();
        }
        $people = array_values(array_unique(array_map('trim', $data['people'])));
        if (count($people) !== count($data['people']) || in_array('', $people, true)) {
            return back()->withErrors(['people' => 'Nama harus diisi dan tidak boleh duplikat.'])->withInput();
        }
        if (($data['discount_percent'] ?? 0) > 0 && ($data['discount_nominal'] ?? 0) > 0) {
            return back()->withErrors(['discount' => 'Gunakan satu jenis diskon saja.'])->withInput();
        }

        $results = [];
        foreach ($people as $person) $results[$person] = ['subtotal'=>0.0,'discount_percent'=>0.0,'discount_nominal'=>0.0,'tax'=>0.0,'service'=>0.0,'total'=>0.0];

        foreach ($data['menu'] as $i => $item) {
            $qtyMap = $data['eaten_qty'][$i] ?? [];
            $assigned = 0;
            foreach ($people as $person) {
                $portion = (int) ($qtyMap[$person] ?? 0);
                $assigned += $portion;
                $results[$person]['subtotal'] += ((float) $data['price'][$i] / (int) $data['qty'][$i]) * $portion;
            }
            if ($assigned !== (int) $data['qty'][$i]) {
                return back()->withErrors(['assignment' => "Jumlah porsi untuk {$item} harus tepat {$data['qty'][$i]}."])->withInput();
            }
        }

        $grandSubtotal = array_sum(array_column($results, 'subtotal'));
        $discountPercent = (float) ($data['discount_percent'] ?? 0);
        $discountNominal = min((float) ($data['discount_nominal'] ?? 0), $grandSubtotal);
        $nominalRemaining = $discountNominal;
        foreach ($results as &$personData) {
            $subtotal = $personData['subtotal'];
            $discPercent = $subtotal * $discountPercent / 100;
            $discNominal = $grandSubtotal > 0 ? min($subtotal, $discountNominal * $subtotal / $grandSubtotal) : 0;
            $after = max(0, $subtotal - $discPercent - $discNominal);
            $personData['subtotal'] = round($subtotal);
            $personData['discount_percent'] = round($discPercent);
            $personData['discount_nominal'] = round($discNominal);
            $personData['tax'] = !empty($data['tax']) ? round($after * .10) : 0;
            $personData['service'] = !empty($data['service']) ? round($after * .05) : 0;
            $personData['total'] = round($after + $personData['tax'] + $personData['service']);
        }
        unset($personData);

        return view('result', [
            'results'=>$results, 'grandSubtotal'=>round($grandSubtotal), 'discountPercent'=>$discountPercent,
            'discountNominal'=>round($discountNominal), 'taxOn'=>!empty($data['tax']), 'serviceOn'=>!empty($data['service']),
            'grandTotal'=>array_sum(array_column($results, 'total')),
        ]);
    }
}
