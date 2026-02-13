<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function result(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATA MASUK
        |--------------------------------------------------------------------------
        | people_totals = subtotal per orang dari choose_people
        | discount_percent = diskon persen (opsional)
        | discount_nominal = diskon nominal (opsional)
        | tax = checkbox pajak
        | service = checkbox service
        */

        $peopleTotals = $request->input('people_totals', []);
        $discountPercent = (float) $request->input('discount_percent', 0);
        $discountNominal = (float) $request->input('discount_nominal', 0);
        $taxOn = $request->has('tax');
        $serviceOn = $request->has('service');

        // Validasi diskon
        if ($discountPercent > 0 && $discountNominal > 0) {
            return back()->withErrors('Isi salah satu diskon saja');
        }

        $totalPeople = count($peopleTotals);
        $grandSubtotal = array_sum($peopleTotals);

        // ================= DISKON =================
        // Diskon persen → proporsional
        // Diskon nominal → dibagi rata per orang
        $discountPercentTotal = 0;
        if ($discountPercent > 0) {
            $discountPercentTotal = $grandSubtotal * ($discountPercent / 100);
        }

        $discountNominalPerPerson = 0;
        if ($discountNominal > 0 && $totalPeople > 0) {
            $discountNominalPerPerson = $discountNominal / $totalPeople;
        }

        // ================= HITUNG PER ORANG =================
        $results = [];

        foreach ($peopleTotals as $name => $subtotal) {

            // Diskon persen proporsional
            $discountFromPercent = 0;
            if ($discountPercentTotal > 0) {
                $ratio = $subtotal / $grandSubtotal;
                $discountFromPercent = $discountPercentTotal * $ratio;
            }

            // Diskon nominal dibagi rata
            $discountFromNominal = min($discountNominalPerPerson, $subtotal);

            $afterDiscount = $subtotal - $discountFromPercent - $discountFromNominal;

            // Pajak & service dihitung SETELAH diskon
            $tax = $taxOn ? $afterDiscount * 0.10 : 0;
            $service = $serviceOn ? $afterDiscount * 0.05 : 0;

            $finalTotal = $afterDiscount + $tax + $service;

            $results[$name] = [
                'subtotal' => round($subtotal),
                'discount_percent' => round($discountFromPercent),
                'discount_nominal' => round($discountFromNominal),
                'tax' => round($tax),
                'service' => round($service),
                'total' => round($finalTotal),
            ];
        }

        // ================= GRAND TOTAL =================
        $grandTotal = array_sum(array_column($results, 'total'));

        return view('result', [
            'results' => $results,
            'grandSubtotal' => round($grandSubtotal),
            'discountPercent' => $discountPercent,
            'discountNominal' => round($discountNominal),
            'taxOn' => $taxOn,
            'serviceOn' => $serviceOn,
            'grandTotal' => round($grandTotal),
        ]);
    }
}
