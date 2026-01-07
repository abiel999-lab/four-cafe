<x-customer-layout title="Checkout - FOUR">
<div class="mx-auto max-w-2xl">
  <div class="rounded-2xl bg-white/70 border border-black/10 p-5">
    <div class="text-xl font-bold mb-4">Checkout</div>

    @if(session('error'))
      <div class="mb-3 rounded-xl bg-red-100 p-3 text-red-900">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('customer.checkout.store') }}" class="flex flex-col gap-4">
      @csrf

      <div>
        <label class="text-sm font-semibold">Nama Pemesan</label>
        <input name="customer_name" placeholder="Contoh: Andi"
               class="mt-1 w-full rounded-xl border-black/10 bg-white/70 focus:border-brand-primary focus:ring-brand-primary"
               required>
      </div>

      <div class="rounded-2xl border border-black/10 bg-black/5 p-4">
        <div class="font-semibold mb-2">Ringkasan</div>

        <div class="flex flex-col gap-3">
          @foreach($lines as $line)
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="font-semibold">{{ $line['name'] }} <span class="opacity-70">x{{ $line['qty'] }}</span></div>

                @if(!empty($line['options']))
                  <div class="text-xs opacity-70">Add-ons: {{ implode(', ', $line['options']) }}</div>
                @endif

                @if(!empty($line['notes']))
                  <div class="text-xs opacity-70">Catatan: {{ $line['notes'] }}</div>
                @endif
              </div>
              <div class="font-semibold">{{ \App\Support\Money::rupiah((int)$line['line_total']) }}</div>
            </div>
          @endforeach
        </div>

        <div class="mt-4 flex items-center justify-between border-t border-black/10 pt-3">
          <div class="font-bold">Total</div>
          <div class="text-lg font-extrabold">{{ \App\Support\Money::rupiah((int)$total) }}</div>
        </div>
      </div>

      <button class="w-full h-12 rounded-2xl bg-brand-primary text-brand-surface font-bold text-lg">
        Buat Invoice (Bayar di Kasir)
      </button>

      <a href="{{ route('customer.menu') }}" class="text-center text-sm underline opacity-70">
        Kembali ke Menu
      </a>
    </form>
  </div>
</div>
</x-customer-layout>
