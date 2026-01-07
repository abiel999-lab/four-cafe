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
    public function bulk(Request $request)
    {
        $qty = $request->input('qty', []);
        $notes = $request->input('notes', []);
        $optionIds = $request->input('option_ids', []);

        $cart = [];

        foreach ($qty as $productId => $q) {
            $productId = (int)$productId;
            $q = (int)$q;

            if ($productId <= 0 || $q <= 0) continue;

            $cart[] = [
                'product_id' => $productId,
                'quantity' => $q,
                'notes' => $notes[$productId] ?? null,
                'option_ids' => isset($optionIds[$productId]) ? (array)$optionIds[$productId] : [],
            ];
        }

        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Isi qty minimal 1 item untuk checkout.');
        }

        session(['cart' => $cart]);

        return redirect()->route('customer.checkout.show');
    }

    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong.');
        }

        // hitung total untuk tampilan
        $total = 0;
        $lines = [];

        foreach ($cart as $row) {
            $product = Product::find($row['product_id']);
            if (!$product) continue;

            $qty = (int)($row['quantity'] ?? 1);
            $qty = max(1, min(99, $qty));

            $optionsTotal = 0;
            $optNames = [];

            $ids = $row['option_ids'] ?? [];
            if (!empty($ids)) {
                $options = Option::whereIn('id', $ids)->get();
                foreach ($options as $op) {
                    $optionsTotal += (int)$op->price;
                    $optNames[] = $op->name;
                }
            }

            $lineTotal = ((int)$product->price + $optionsTotal) * $qty;
            $total += $lineTotal;

            $lines[] = [
                'name' => $product->name,
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

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong.');
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart) {

                $order = new Order();
                $order->code = $this->generateCode();
                $order->customer_name = $validated['customer_name'];
                $order->order_type = 'counter'; // tidak pakai dinein/takeaway
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

                    $product = Product::query()->lockForUpdate()->findOrFail($productId);

                    $qty = (int)($row['quantity'] ?? 1);
                    $qty = max(1, min(99, $qty));

                    if ((int)$product->stock < $qty) {
                        throw new \RuntimeException("Stock {$product->name} tidak cukup. Sisa: {$product->stock}");
                    }

                    $unitPrice = (int)$product->price;

                    $optionsPayload = [];
                    $optionsTotal = 0;

                    $ids = $row['option_ids'] ?? [];
                    if (!empty($ids)) {
                        $options = Option::whereIn('id', $ids)->get();
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
