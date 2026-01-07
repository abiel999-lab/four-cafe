<x-seller-layout title="Order Detail - FOUR">
@php
    $tabs = [
        'unpaid'    => 'Belum Bayar',
        'paid'      => 'Sudah Bayar',
        'preparing' => 'Diproses',
        'ready'     => 'Siap',
        'completed' => 'Selesai',
        'cancelled' => 'Batal',
    ];

    $badge = function($key) {
        return match($key) {
            'unpaid'    => 'bg-amber-100 text-amber-900',
            'paid'      => 'bg-green-100 text-green-900',
            'preparing' => 'bg-blue-100 text-blue-900',
            'ready'     => 'bg-purple-100 text-purple-900',
            'completed' => 'bg-gray-200 text-gray-900',
            'cancelled' => 'bg-red-100 text-red-900',
            default     => 'bg-gray-200 text-gray-900',
        };
    };

    $calcTotal = (int) $order->items->sum('line_total');
    $total = (int) ($order->total ?: $calcTotal);
@endphp

<div class="flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Order Detail</div>
            <div class="text-sm opacity-70">{{ $order->code }}</div>
        </div>

        <a href="{{ route('seller.orders.index') }}"
           class="h-11 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
            ← Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="rounded-2xl bg-white/70 border border-black/10 p-4 lg:col-span-2">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <div class="font-semibold text-lg">{{ $order->customer_name }}</div>
                    <div class="text-sm opacity-80">
                        {{ $order->order_type === 'dinein' ? 'Dine-in' : 'Takeaway' }}
                        @if($order->table_code)
                            • Meja: <b>{{ $order->table_code }}</b>
                        @endif
                    </div>
                    <div class="text-sm opacity-70 mt-1">
                        Dibuat: {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="flex flex-col items-start sm:items-end gap-2">
                    <span class="px-3 py-2 rounded-xl text-sm font-semibold {{ $badge($order->status) }}">
                        Status: {{ $tabs[$order->status] ?? $order->status }}
                    </span>

                    <span class="px-3 py-2 rounded-xl text-sm font-semibold {{ $order->is_paid ? 'bg-green-100 text-green-900' : 'bg-amber-100 text-amber-900' }}">
                        Payment: {{ $order->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
            <div class="text-sm opacity-70">Total</div>
            <div class="text-2xl font-bold">
                {{ \App\Support\Money::rupiah($total) }}
            </div>

            <div class="mt-3 flex flex-col gap-2">
                <form method="POST" action="{{ route('seller.orders.togglePaid', $order) }}">
                    @csrf
                    <button class="w-full h-11 rounded-xl font-semibold {{ $order->is_paid ? 'bg-gray-200 text-gray-900' : 'bg-brand-primary text-brand-surface' }}">
                        {{ $order->is_paid ? 'Batalkan Paid' : 'Tandai Sudah Bayar' }}
                    </button>
                </form>

                <a href="{{ route('seller.orders.invoice', $order) }}"
                   class="w-full h-11 rounded-xl border border-black/10 bg-white/60 grid place-items-center font-semibold">
                    Lihat Invoice
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
        <div class="font-semibold text-lg mb-3">Items</div>

        <div class="overflow-x-auto">
            <table class="min-w-[760px] w-full text-sm">
                <thead class="bg-black/5">
                    <tr class="text-left">
                        <th class="p-3">Produk</th>
                        <th class="p-3">Qty</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Add-ons</th>
                        <th class="p-3">Catatan</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $it)
                        <tr class="border-t border-black/10 align-top">
                            <td class="p-3 font-semibold">{{ $it->product_name }}</td>
                            <td class="p-3">{{ $it->quantity }}</td>
                            <td class="p-3">{{ \App\Support\Money::rupiah((int)$it->unit_price) }}</td>
                            <td class="p-3">
                                @if($it->options && count($it->options))
                                    <ul class="list-disc pl-5">
                                        @foreach($it->options as $op)
                                            <li>{{ $op['name'] }} (+{{ \App\Support\Money::rupiah((int)$op['price']) }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="opacity-60">-</span>
                                @endif
                            </td>
                            <td class="p-3">{{ $it->notes ?: '-' }}</td>
                            <td class="p-3 text-right font-semibold">
                                {{ \App\Support\Money::rupiah((int)$it->line_total) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-black/10">
                        <td class="p-3 font-semibold" colspan="5">Grand Total</td>
                        <td class="p-3 text-right font-bold text-base">
                            {{ \App\Support\Money::rupiah($total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
        <div class="font-semibold text-lg mb-3">Update Status</div>

        <form method="POST" action="{{ route('seller.orders.updateStatus', $order) }}" class="flex flex-col sm:flex-row gap-2">
            @csrf
            <select name="status" class="w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                @foreach($tabs as $key => $label)
                    <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                @endforeach
            </select>

            <button class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">
                Simpan
            </button>
        </form>
    </div>
</div>
</x-seller-layout>
