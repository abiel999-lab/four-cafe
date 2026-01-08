@extends('layouts.customer')

@section('content')
@php
    use App\Support\Money;
    $subtotal = 0;
@endphp

<div class="rounded-2xl bg-white/70 border border-black/10 p-4">
    <div class="text-lg font-semibold mb-3">Keranjang</div>

    @if(empty($cart))
        <div class="opacity-80">Keranjang kosong. Silakan pilih menu.</div>
        <a href="{{ route('customer.menu') }}"
           class="inline-block mt-3 px-4 py-2 rounded-xl bg-brand-primary text-brand-surface">
            Kembali ke Menu
        </a>
    @else
        <div class="flex flex-col gap-3">
            @foreach($cart as $item)
                @php
                    $unit = (int)($item['unit_price'] ?? 0);
                    $optTotal = (int)($item['options_total'] ?? 0);
                    $qty = (int)($item['quantity'] ?? 0);
                    $line = ($unit + $optTotal) * $qty;
                    $subtotal += $line;
                @endphp

                <div class="rounded-xl border border-black/10 bg-white/60 p-3">
                    <div class="flex justify-between gap-2">
                        <div>
                            <div class="font-semibold">{{ $item['product_name'] ?? 'Produk' }}</div>
                            <div class="text-sm opacity-80">
                                {{ Money::rupiah($unit + $optTotal) }} x {{ $qty }}
                            </div>

                            @if(!empty($item['options']))
                                <div class="text-xs mt-1 opacity-80">
                                    Add-ons:
                                    {{ collect($item['options'])->map(fn($o) => $o['name'])->implode(', ') }}
                                </div>
                            @endif

                            @if(!empty($item['notes']))
                                <div class="text-xs mt-1 opacity-80">Catatan: {{ $item['notes'] }}</div>
                            @endif
                        </div>

                        <div class="text-right">
                            <div class="font-semibold">{{ Money::rupiah($line) }}</div>
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2 items-center flex-wrap">
                        <form method="POST" action="{{ route('customer.cart.update') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="key" value="{{ $item['key'] ?? '' }}">
                            <input type="number" name="quantity" value="{{ $qty }}" min="1" max="99"
                                   class="w-24 rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                            <button class="px-3 py-2 rounded-xl bg-brand-primary text-brand-surface">
                                Update
                            </button>
                        </form>

                        <form method="POST" action="{{ route('customer.cart.remove') }}">
                            @csrf
                            <input type="hidden" name="key" value="{{ $item['key'] ?? '' }}">
                            <button class="px-3 py-2 rounded-xl border border-black/10 bg-white/60">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div class="font-semibold">Total</div>
            <div class="text-lg font-bold">{{ Money::rupiah($subtotal) }}</div>
        </div>

        <div class="mt-4 flex gap-2">
            <a href="{{ route('customer.menu') }}"
               class="px-4 py-2 rounded-xl border border-black/10 bg-white/60">
                Tambah Menu
            </a>

            <a href="{{ route('customer.checkout.show') }}"
               class="flex-1 px-4 py-2 rounded-xl bg-brand-primary text-brand-surface text-center">
                Checkout
            </a>
        </div>
    @endif
</div>
@endsection
