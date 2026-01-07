<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        $todayOrders = Order::query()->where('created_at', '>=', $today)->count();
        $todayPaidRevenue = Order::query()->where('status', '!=', 'unpaid')->where('created_at', '>=', $today)->sum('total');

        $lowStock = Product::query()->where('stock', '<=', 3)->count();
        $outOfStock = Product::query()->where('stock', '<=', 0)->count();

        $bestSellersToday = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as qty'))
            ->where('orders.status', '!=', 'unpaid')
            ->where('orders.created_at', '>=', $today)
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        return view('seller.dashboard', compact(
            'todayOrders','todayPaidRevenue','lowStock','outOfStock','bestSellersToday'
        ));
    }
}
