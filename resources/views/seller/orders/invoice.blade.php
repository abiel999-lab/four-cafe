<x-seller-layout title="Invoice - FOUR">
@php
    $calcTotal = (int) $order->items->sum('line_total'); // aman kalau grand_total kosong
    $total = (int) ($order->grand_total ?: $calcTotal);

    $storeName = 'FOUR Cafe & Coffee';
@endphp

<div class="flex flex-col gap-3">
    <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
        <div>
            <div class="text-xl font-bold">Invoice</div>
            <div class="text-sm opacity-70">{{ $order->code }}</div>
        </div>

        <div class="flex gap-2">
            <button onclick="window.print()"
                    class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">
                Print Struk
            </button>

            <a href="{{ route('seller.orders.show', $order) }}"
               class="h-11 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                Kembali
            </a>
        </div>
    </div>

    {{-- STRUK --}}
    <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
        <div id="receipt" class="mx-auto">
            <div class="text-center">
                <div class="font-extrabold text-lg">{{ $storeName }}</div>
                <div class="text-xs opacity-70">Invoice / Struk Pembelian</div>
                <div class="text-xs opacity-70 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>

            <div class="my-3 border-t border-dashed border-black/30"></div>

            <div class="text-sm">
                <div class="flex justify-between">
                    <span>Kode</span>
                    <span class="font-semibold">{{ $order->code }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Nama</span>
                    <span class="font-semibold">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tipe</span>
                    <span class="font-semibold">{{ $order->order_type === 'dinein' ? 'Dine-in' : 'Takeaway' }}</span>
                </div>
                @if($order->table_code)
                    <div class="flex justify-between">
                        <span>Meja</span>
                        <span class="font-semibold">{{ $order->table_code }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Payment</span>
                    <span class="font-semibold">{{ $order->is_paid ? 'PAID' : 'UNPAID' }}</span>
                </div>
            </div>

            <div class="my-3 border-t border-dashed border-black/30"></div>

            <div class="text-sm font-semibold mb-2">Items</div>

            <div class="flex flex-col gap-2 text-sm">
                @foreach($order->items as $it)
                    <div>
                        <div class="flex justify-between font-semibold">
                            <span>{{ $it->product_name }}</span>
                            <span>{{ \App\Support\Money::rupiah((int)$it->line_total) }}</span>
                        </div>
                        <div class="flex justify-between text-xs opacity-80">
                            <span>{{ $it->quantity }} x {{ \App\Support\Money::rupiah((int)$it->unit_price) }}</span>
                            <span></span>
                        </div>

                        @if($it->options && count($it->options))
                            <div class="mt-1 text-xs opacity-80">
                                @foreach($it->options as $op)
                                    <div class="flex justify-between">
                                        <span>+ {{ $op['name'] }}</span>
                                        <span>{{ \App\Support\Money::rupiah((int)$op['price']) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($it->notes)
                            <div class="mt-1 text-xs">
                                Catatan: <span class="opacity-80">{{ $it->notes }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="my-3 border-t border-dashed border-black/30"></div>

            <div class="text-sm">
                <div class="flex justify-between font-extrabold text-base">
                    <span>TOTAL</span>
                    <span>{{ \App\Support\Money::rupiah($total) }}</span>
                </div>
            </div>

            <div class="my-3 border-t border-dashed border-black/30"></div>

            <div class="text-center text-xs opacity-80">
                Terima kasih 🙏<br>
                Silakan datang kembali.
            </div>
        </div>
    </div>
</div>

{{-- STYLE PRINT: struk thermal 80mm --}}
<style>
/* area struk */
#receipt{
    width: 380px; /* default layar */
    max-width: 100%;
}

/* PRINT MODE */
@media print {
    /* sembunyikan semua kecuali struk */
    body * { visibility: hidden; }
    #receipt, #receipt * { visibility: visible; }

    /* posisikan struk */
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;           /* thermal 80mm */
        padding: 0;
        margin: 0;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    /* hilangkan background */
    body {
        background: white !important;
    }
}
</style>
</x-seller-layout>
