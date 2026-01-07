@extends('layouts.customer')

@section('content')
@php
    use App\Support\Money;
@endphp

<div class="rounded-2xl bg-white/70 border border-black/10 p-6 text-center">
    <div class="text-2xl font-bold text-brand-primary">PESANAN BERHASIL</div>
    <div class="mt-2 opacity-80">Silakan bayar di kasir dengan menunjukkan kode berikut.</div>

    <div class="mt-5 rounded-2xl bg-brand-primary text-brand-surface p-5">
        <div class="text-sm opacity-90">Kode Invoice</div>
        <div class="text-2xl sm:text-3xl font-extrabold tracking-wide">{{ $order->code }}</div>
        <div class="mt-3 text-sm">Atas nama: <span class="font-semibold">{{ $order->customer_name }}</span></div>
    </div>

    <div class="mt-5 rounded-xl border border-black/10 bg-white/60 p-4 text-left">
        <div class="font-semibold mb-2">Ringkasan</div>
        <div class="flex flex-col gap-2">
            @foreach($order->items as $it)
                <div class="flex justify-between gap-2">
                    <div class="text-sm">
                        <div class="font-semibold">{{ $it->product_name }} x{{ $it->quantity }}</div>
                        @if($it->options)
                            <div class="text-xs opacity-80">Add-ons: {{ collect($it->options)->pluck('name')->implode(', ') }}</div>
                        @endif
                        @if($it->notes)
                            <div class="text-xs opacity-80">Catatan: {{ $it->notes }}</div>
                        @endif
                    </div>
                    <div class="text-sm font-semibold">{{ Money::rupiah($it->line_total) }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 flex justify-between border-t border-black/10 pt-3">
            <div class="font-semibold">Total</div>
            <div class="text-lg font-bold">{{ Money::rupiah($order->total) }}</div>
        </div>
    </div>

    <div class="mt-5 flex flex-col sm:flex-row gap-2 justify-center">
        <a href="{{ route('customer.menu') }}" class="px-4 py-3 rounded-xl bg-brand-primary text-brand-surface font-semibold">
            Pesan Lagi
        </a>
        <button type="button" onclick="navigator.clipboard.writeText('{{ $order->code }}')" class="px-4 py-3 rounded-xl border border-black/10 bg-white/60">
            Salin Kode
        </button>
    </div>
</div>
@endsection
