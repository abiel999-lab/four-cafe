<x-seller-layout title="Orders - FOUR">
@php
  $tabs = [
    'all'       => 'Semua',
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
@endphp

<div class="flex flex-col gap-4">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <div class="text-xl font-bold">Orders</div>
      <div class="text-sm opacity-70">Kelola pesanan masuk</div>
    </div>

    <form method="GET" action="{{ route('seller.orders.index') }}" class="flex gap-2 w-full sm:w-auto">
      <input type="hidden" name="status" value="{{ $status ?? 'unpaid' }}">
      <input name="q" value="{{ $q ?? '' }}" placeholder="Cari: nama / kode invoice..."
             class="w-full sm:w-80 rounded-xl border-black/10 bg-white/60 focus:border-brand-primary focus:ring-brand-primary">
      <button class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">
        Cari
      </button>
    </form>
  </div>

  <div class="flex flex-wrap gap-2">
    @foreach($tabs as $key => $label)
      <a href="{{ route('seller.orders.index', ['status'=>$key, 'q'=>$q ?? '']) }}"
         class="px-4 h-11 rounded-xl border border-black/10 grid place-items-center
                {{ ($status ?? 'unpaid') === $key ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>

  <div class="grid grid-cols-1 gap-3">
    @forelse($orders as $order)
      @php
        // FIX total list: pakai kolom total
        $total = (int) ($order->total ?? 0);
      @endphp

      <a href="{{ route('seller.orders.show', $order) }}"
         class="rounded-2xl bg-white/70 border border-black/10 p-4 hover:bg-white/80 transition">
        <div class="flex items-start justify-between gap-3">
          <div>
            <div class="font-semibold text-lg">{{ $order->customer_name }}</div>
            <div class="text-sm opacity-70">{{ $order->code }}</div>

            <div class="mt-2 flex flex-wrap gap-2">
              <span class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $badge($order->status) }}">
                {{ $tabs[$order->status] ?? $order->status }}
              </span>
              <span class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $order->paid_at ? 'bg-green-100 text-green-900' : 'bg-amber-100 text-amber-900' }}">
                {{ $order->paid_at ? 'Paid' : 'Unpaid' }}
              </span>
            </div>
          </div>

          <div class="text-right">
            <div class="text-sm opacity-70">{{ $order->order_type === 'dinein' ? 'Dine-in' : 'Takeaway' }}</div>
            <div class="text-sm opacity-70">{{ $order->created_at->format('H:i') }}</div>
            <div class="mt-2 font-extrabold">{{ \App\Support\Money::rupiah($total) }}</div>
          </div>
        </div>
      </a>
    @empty
      <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
        Tidak ada order.
      </div>
    @endforelse
  </div>

  <div>
    {{ $orders->links() }}
  </div>
</div>
</x-seller-layout>
