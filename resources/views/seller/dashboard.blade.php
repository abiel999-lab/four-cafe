<x-seller-layout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
            <div class="text-sm opacity-70">Today Orders</div>
            <div class="text-3xl font-bold">{{ $todayOrders }}</div>
        </div>

        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
            <div class="text-sm opacity-70">Today Revenue (Paid+)</div>
            <div class="text-2xl font-bold">{{ \App\Support\Money::rupiah((int)$todayPaidRevenue) }}</div>
        </div>

        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
            <div class="text-sm opacity-70">Low Stock (≤ 3)</div>
            <div class="text-3xl font-bold">{{ $lowStock }}</div>
        </div>

        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
            <div class="text-sm opacity-70">Out of Stock</div>
            <div class="text-3xl font-bold">{{ $outOfStock }}</div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white/70 border border-black/10 p-4">
        <div class="text-lg font-semibold">Best Seller (Today)</div>

        <div class="mt-3">
            @if($bestSellersToday->isEmpty())
                <div class="opacity-80">Belum ada penjualan hari ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-black/10">
                                <th class="py-2">Produk</th>
                                <th class="py-2">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellersToday as $row)
                                <tr class="border-b border-black/5">
                                    <td class="py-2 font-semibold">{{ $row->product_name }}</td>
                                    <td class="py-2">{{ $row->qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-seller-layout>
