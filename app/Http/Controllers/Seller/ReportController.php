<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\OrderItem;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'today');

        $start = match ($range) {
            'today' => Carbon::today(),
            'month' => Carbon::now()->startOfMonth(),
            'year'  => Carbon::now()->startOfYear(),
            default => Carbon::today(),
        };

        $end = Carbon::now();

        // Best seller (Qty) & Revenue per produk
        $productAgg = OrderItem::query()
            ->whereHas('order', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->whereNotNull('paid_at'); // hanya yang sudah bayar
            })
            ->selectRaw('product_name as name, SUM(quantity) as qty, SUM(line_total) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->get();

        // Revenue by date (untuk chart)
        $revenueByDate = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('paid_at')
            ->selectRaw('DATE(created_at) as d, SUM(total) as revenue')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        return view('seller.reports.index', [
            'range' => $range,
            'productAgg' => $productAgg,
            'revenueByDate' => $revenueByDate,
        ]);
    }
}
