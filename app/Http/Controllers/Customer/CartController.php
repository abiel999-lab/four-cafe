<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        // ✅ MIGRASI: kalau cart masih format lama (list dari bulk checkout),
        // ubah jadi format baru (keyed md5) agar blade tidak error.
        if ($this->isLegacyCart($cart)) {
            $cart = $this->migrateLegacyCart($cart);
            $request->session()->put('cart', $cart);
        }

        return $cart;
    }

    private function isLegacyCart(array $cart): bool
    {
        // legacy biasanya: [ [product_id, quantity, notes, option_ids], ... ]
        // dan tidak punya 'unit_price'
        if (empty($cart)) return false;

        $first = reset($cart);

        return is_array($first)
            && array_key_exists('product_id', $first)
            && !array_key_exists('unit_price', $first);
    }

    private function migrateLegacyCart(array $legacy): array
    {
        $new = [];

        foreach ($legacy as $row) {
            $productId = (int)($row['product_id'] ?? 0);
            $qty = (int)($row['quantity'] ?? 0);
            if ($productId <= 0 || $qty <= 0) continue;

            $product = Product::query()
                ->with(['options' => fn($q) => $q->where('is_active', true)])
                ->find($productId);

            if (!$product) continue;

            $optionIds = collect($row['option_ids'] ?? [])
                ->map(fn($v) => (int)$v)
                ->filter(fn($v) => $v > 0)
                ->sort()
                ->values()
                ->all();

            $notes = (($row['notes'] ?? '') ?: '');
            $key = md5($product->id.'|'.implode(',', $optionIds).'|'.$notes);

            $options = $product->options->whereIn('id', $optionIds)->values();
            $optionsTotal = (int)$options->sum('price');

            if (!isset($new[$key])) {
                $new[$key] = [
                    'key' => $key,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => (int)$product->price,
                    'quantity' => 0,
                    'option_ids' => $optionIds,
                    'options' => $options->map(fn($o) => [
                        'id' => $o->id,
                        'name' => $o->name,
                        'price' => (int)$o->price,
                    ])->all(),
                    'options_total' => $optionsTotal,
                    'notes' => ($notes !== '' ? $notes : null),
                ];
            }

            $new[$key]['quantity'] = min(99, (int)$new[$key]['quantity'] + $qty);
        }

        return $new;
    }

    private function saveCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    public function show(Request $request)
    {
        $cart = $this->getCart($request);
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'quantity' => ['nullable','integer','min:1','max:99'],
            'option_ids' => ['nullable','array'],
            'option_ids.*' => ['integer','exists:options,id'],
            'notes' => ['nullable','string','max:255'],
        ]);

        $product = Product::query()
            ->with(['options' => fn($q) => $q->where('is_active', true)])
            ->findOrFail($data['product_id']);

        $qty = max(1, min(99, (int)($data['quantity'] ?? 1)));

        $optionIds = collect($data['option_ids'] ?? [])
            ->map(fn($v) => (int)$v)
            ->filter(fn($v) => $v > 0)
            ->sort()
            ->values()
            ->all();

        $options = $product->options->whereIn('id', $optionIds)->values();
        $optionsTotal = (int)$options->sum('price');

        $notes = (($data['notes'] ?? '') ?: '');
        $key = md5($product->id.'|'.implode(',', $optionIds).'|'.$notes);

        $cart = $this->getCart($request);

        if (!isset($cart[$key])) {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => (int)$product->price,
                'quantity' => 0,
                'option_ids' => $optionIds,
                'options' => $options->map(fn($o) => [
                    'id' => $o->id,
                    'name' => $o->name,
                    'price' => (int)$o->price,
                ])->all(),
                'options_total' => $optionsTotal,
                'notes' => ($notes !== '' ? $notes : null),
            ];
        }

        $cart[$key]['quantity'] = min(99, (int)$cart[$key]['quantity'] + $qty);
        $this->saveCart($request, $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'key' => ['required','string'],
            'quantity' => ['required','integer','min:1','max:99'],
        ]);

        $cart = $this->getCart($request);

        if (isset($cart[$data['key']])) {
            $cart[$data['key']]['quantity'] = (int)$data['quantity'];
            $this->saveCart($request, $cart);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'key' => ['required','string'],
        ]);

        $cart = $this->getCart($request);
        unset($cart[$data['key']]);
        $this->saveCart($request, $cart);

        return back()->with('success', 'Item dihapus.');
    }
}
