<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SplitBillController extends Controller
{
    public function showMenuForm()
    {
        return view('menu');
    }

    /**
     * STEP 2
     * Terima menu + harga + qty
     */
    public function submitMenu(Request $request)
    {
        $request->validate([
            'menu' => 'required|array|min:1',
            'menu.*' => 'required|string',

            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',

            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        $menu = $request->menu;
        $price = $request->price;
        $qty   = $request->qty;

        // TOTAL harga per menu (harga × qty)
        $totalPrice = [];
        foreach ($price as $i => $p) {
            $totalPrice[$i] = $p * $qty[$i];
        }

        $qty = [1, 4];

        return view('choose_people', [
            'menu'  => $menu,
            'price' => $totalPrice,
            'qty'   => $qty,
        ]);
    }

    /** 
     * STEP 3
     * Hitung split + pajak/service proporsional
     */
    public function calculate(Request $request)
    {
        $menu   = $request->menu;
        $price  = $request->price;
        $eaten  = $request->eaten ?? [];
        $people = $request->people;

        $result = [];

        // Init subtotal
        foreach ($people as $person) {
            $result[$person] = [
                'subtotal' => 0,
                'tax' => 0,
                'service' => 0,
                'total' => 0,
            ];
        }

        // Split menu
        foreach ($menu as $i => $menuName) {
            if (!empty($eaten[$i])) {
                $eaters = $eaten[$i];
                $split  = $price[$i] / count($eaters);

                foreach ($eaters as $person) {
                    $result[$person]['subtotal'] += $split;
                }
            }
        }

        // TOTAL subtotal semua orang
        $grandSubtotal = array_sum(
            array_column($result, 'subtotal')
        );

        $taxTotal = $request->has('tax') ? $grandSubtotal * 0.10 : 0;
        $serviceTotal = $request->has('service') ? $grandSubtotal * 0.05 : 0;

        // Pajak & service PROPORSIONAL
        foreach ($result as $person => &$data) {
            if ($grandSubtotal > 0) {
                $ratio = $data['subtotal'] / $grandSubtotal;
                $data['tax'] = $taxTotal * $ratio;
                $data['service'] = $serviceTotal * $ratio;
            }

            $data['total'] =
                $data['subtotal'] +
                $data['tax'] +
                $data['service'];
        }

        return view('result', compact('result', 'taxTotal', 'serviceTotal'));
    }
}
