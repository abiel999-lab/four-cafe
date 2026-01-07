<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $data = [
            'store_name' => Setting::get('store_name', 'FOUR Cafe & Coffee'),
            'store_address' => Setting::get('store_address', ''),
            'payment_note' => Setting::get('payment_note', 'Pembayaran dilakukan di kasir.'),
        ];

        return view('seller.settings.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required','string','max:100'],
            'store_address' => ['nullable','string','max:255'],
            'payment_note' => ['nullable','string','max:255'],
        ]);

        Setting::set('store_name', $data['store_name']);
        Setting::set('store_address', $data['store_address'] ?? '');
        Setting::set('payment_note', $data['payment_note'] ?? '');

        return back()->with('success', 'Settings disimpan.');
    }
}
