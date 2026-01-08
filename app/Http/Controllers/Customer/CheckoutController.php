<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Option;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong.');
        }

        $total = 0;
        $lines = [];

        foreach ($cart as $row) {
            $qty = (int)($row['quantity'] ?? 1);
            $qty = max(1, min(99, $qty));

            $unitPrice = (int)($row['unit_price'] ?? 0);

            // options dari cart
            $optNames = [];
            $optionsTotal = 0;
            foreach (($row['options'] ?? []) as $op) {
                $optNames[] = $op['name'];
                $optionsTotal += (int)($op['price'] ?? 0);
            }

            $lineTotal = ($unitPrice + $optionsTotal) * $qty;
            $total += $lineTotal;

            $lines[] = [
                'name' => $row['product_name'] ?? 'Produk',
                'qty' => $qty,
                'line_total' => $lineTotal,
                'options' => $optNames,
                'notes' => $row['notes'] ?? null,
            ];
        }

        return view('customer.checkout', [
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:80'],
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong.');
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart) {

                $order = new Order();
                $order->code = $this->generateCode();
                $order->customer_name = $validated['customer_name'];
                $order->order_type = 'counter';
                $order->table_code = null;
                $order->status = 'unpaid';
                $order->subtotal = 0;
                $order->total = 0;
                $order->paid_at = null;
                $order->save();

                $total = 0;

                foreach ($cart as $row) {
                    $productId = (int)($row['product_id'] ?? 0);
                    if ($productId <= 0) continue;

                    $qty = (int)($row['quantity'] ?? 1);
                    $qty = max(1, min(99, $qty));

                    // lock product untuk aman stok
                    $product = Product::query()->lockForUpdate()->findOrFail($productId);

                    if ((int)$product->stock < $qty) {
                        throw new \RuntimeException("Stock {$product->name} tidak cukup. Sisa: {$product->stock}");
                    }

                    $unitPrice = (int)$product->price;

                    // untuk keamanan, options diambil ulang dari DB berdasarkan option_ids
                    $optionIds = collect($row['option_ids'] ?? [])
                        ->map(fn($v) => (int)$v)
                        ->filter(fn($v) => $v > 0)
                        ->values()
                        ->all();

                    $optionsPayload = [];
                    $optionsTotal = 0;

                    if (!empty($optionIds)) {
                        $options = Option::whereIn('id', $optionIds)->get();
                        foreach ($options as $op) {
                            $optionsPayload[] = [
                                'id' => $op->id,
                                'name' => $op->name,
                                'price' => (int)$op->price,
                            ];
                            $optionsTotal += (int)$op->price;
                        }
                    }

                    $lineTotal = ($unitPrice + $optionsTotal) * $qty;
                    $total += $lineTotal;

                    $item = new OrderItem();
                    $item->order_id = $order->id;
                    $item->product_id = $product->id;
                    $item->product_name = $product->name;
                    $item->quantity = $qty;
                    $item->unit_price = $unitPrice;
                    $item->options = $optionsPayload;
                    $item->notes = $row['notes'] ?? null;
                    $item->line_total = $lineTotal;
                    $item->save();

                    // potong stock
                    $product->stock = (int)$product->stock - $qty;
                    $product->save();
                }

                $order->subtotal = $total;
                $order->total = $total;
                $order->save();

                return $order;
            });

            session()->forget('cart');

            return redirect()->route('customer.invoice', $order)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        $order->load('items');
        return view('customer.invoice', ['order' => $order]);
    }

    private function generateCode(): string
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = "FOUR-{$date}-";

        $last = Order::where('code', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = 1;
        if ($last) {
            $lastNumber = (int) substr($last->code, -4);
            $next = $lastNumber + 1;
        }

        return $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
    }
}
