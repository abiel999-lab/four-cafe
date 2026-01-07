<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(Request $request): array
    {
        return $request->session()->get('cart', []);
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

        $product = Product::query()->with(['options' => fn($q) => $q->where('is_active', true)])->findOrFail($data['product_id']);
        $qty = (int)($data['quantity'] ?? 1);

        $optionIds = collect($data['option_ids'] ?? [])->map(fn($v) => (int)$v)->sort()->values()->all();
        $options = $product->options->whereIn('id', $optionIds)->values();

        $optionsTotal = (int)$options->sum('price');
        $unit = (int)$product->price + $optionsTotal;

        // key unik berdasarkan produk+options+notes
        $key = md5($product->id.'|'.implode(',', $optionIds).'|'.(($data['notes'] ?? '') ?: ''));

        $cart = $this->getCart($request);

        if (!isset($cart[$key])) {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => (int)$product->price,
                'quantity' => 0,
                'options' => $options->map(fn($o) => ['id'=>$o->id,'name'=>$o->name,'price'=>(int)$o->price])->all(),
                'options_total' => $optionsTotal,
                'notes' => ($data['notes'] ?? null),
            ];
        }

        $cart[$key]['quantity'] = min(99, (int)$cart[$key]['quantity'] + $qty);
        $this->saveCart($request, $cart);

        return redirect()->route('customer.cart.show')->with('success', 'Produk ditambahkan ke keranjang.');
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
