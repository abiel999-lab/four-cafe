<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'unpaid'); // all/unpaid/paid/...
        $q = trim((string) $request->get('q', ''));

        $orders = Order::query()
            ->when($status && $status !== 'all', fn($qq) => $qq->where('status', $status))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('customer_name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'q' => $q,
        ]);
    }


    public function show(Order $order)
    {
        $order->load(['items']);

        // Sync subtotal & total dari items agar tidak 0
        $calc = (int) $order->items->sum('line_total');

        if ((int)$order->subtotal !== $calc || (int)$order->total !== $calc) {
            $order->subtotal = $calc;
            $order->total = $calc;
            $order->save();
        }

        return view('seller.orders.show', [
            'order' => $order,
        ]);
    }

    public function togglePaid(Order $order)
    {
        // paid_at toggle
        if ($order->paid_at) {
            $order->paid_at = null;

            // kalau status paid balik unpaid (opsional)
            if ($order->status === 'paid') {
                $order->status = 'unpaid';
            }
        } else {
            $order->paid_at = Carbon::now();

            // kalau status unpaid, set paid (opsional)
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
            }
        }

        $order->save();

        return back()->with('success', $order->paid_at ? 'Order ditandai sudah dibayar.' : 'Pembayaran dibatalkan (unpaid).');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:unpaid,paid,preparing,ready,completed,cancelled'],
        ]);

        $order->status = $validated['status'];

        // kalau status jadi paid, set paid_at kalau belum ada
        if ($order->status === 'paid' && !$order->paid_at) {
            $order->paid_at = Carbon::now();
        }

        // kalau status balik unpaid, hapus paid_at (opsional)
        if ($order->status === 'unpaid') {
            $order->paid_at = null;
        }

        $order->save();

        return back()->with('success', 'Status order berhasil diupdate.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items']);

        // Sync subtotal & total juga
        $calc = (int) $order->items->sum('line_total');
        if ((int)$order->subtotal !== $calc || (int)$order->total !== $calc) {
            $order->subtotal = $calc;
            $order->total = $calc;
            $order->save();
        }

        return view('seller.orders.invoice', [
            'order' => $order,
        ]);
    }
}
