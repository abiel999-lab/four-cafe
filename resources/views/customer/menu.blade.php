<x-customer-layout title="Menu - FOUR">
@php
  $q = $q ?? '';
  $category = $category ?? 'all';
@endphp

<form method="GET" action="{{ route('customer.menu') }}" class="mb-4">
  <div class="mx-auto max-w-5xl">
    <div class="rounded-2xl bg-white/60 border border-black/10 p-4">
      <div class="flex flex-col sm:flex-row gap-2">
        <input name="q" value="{{ $q }}" placeholder="Cari menu..."
               class="w-full rounded-xl border-black/10 bg-white/70 focus:border-brand-primary focus:ring-brand-primary">
        <button class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">Cari</button>
      </div>

      <div class="mt-3 flex flex-wrap gap-2">
        <a href="{{ route('customer.menu', ['category'=>'all','q'=>$q]) }}"
           class="px-4 h-10 rounded-xl border border-black/10 grid place-items-center {{ $category==='all' ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
          Semua
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('customer.menu', ['category'=>$cat->id,'q'=>$q]) }}"
             class="px-4 h-10 rounded-xl border border-black/10 grid place-items-center {{ (string)$category===(string)$cat->id ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
            {{ $cat->name }}
          </a>
        @endforeach
      </div>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('customer.checkout.bulk') }}">
  @csrf

  <div class="mx-auto max-w-5xl">
    <div class="flex items-center justify-between mb-3">
      <div class="text-lg font-bold">Menu</div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
      @forelse($products as $p)
        <div class="rounded-2xl bg-white/70 border border-black/10 overflow-hidden">
          {{-- IMAGE AREA --}}
          <div class="aspect-[4/3] bg-black/5">
            @if(!empty($p->image_path))
              <img src="{{ asset('storage/'.$p->image_path) }}" alt="{{ $p->name }}"
                   class="w-full h-full object-cover">
            @else
              <div class="w-full h-full grid place-items-center text-5xl font-extrabold text-brand-primary/70">
                {{ strtoupper(mb_substr($p->name,0,1)) }}
              </div>
            @endif
          </div>

          {{-- CONTENT --}}
          <div class="p-4 flex flex-col gap-3">
            <div>
              <div class="font-bold text-lg leading-tight">{{ $p->name }}</div>
              <div class="text-sm opacity-70">{{ \App\Support\Money::rupiah((int)$p->price) }}</div>

              @if(isset($p->stock))
                <div class="text-xs mt-1 opacity-70">Stock: {{ (int)$p->stock }}</div>
              @endif
            </div>

            {{-- OPTIONS --}}
            @if($p->options && $p->options->count())
              <div class="text-sm">
                <div class="font-semibold mb-1">Add-ons:</div>
                <div class="flex flex-col gap-2">
                  @foreach($p->options as $op)
                    <label class="flex items-center gap-2 rounded-xl border border-black/10 bg-white/60 px-3 py-2">
                      <input type="checkbox" name="option_ids[{{ $p->id }}][]" value="{{ $op->id }}"
                             class="rounded border-black/20 text-brand-primary focus:ring-brand-primary">
                      <span class="text-sm">{{ $op->name }}</span>
                      <span class="ml-auto text-xs opacity-70">+{{ \App\Support\Money::rupiah((int)$op->price) }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            {{-- NOTES --}}
            <input name="notes[{{ $p->id }}]" placeholder="Catatan (opsional)"
                   class="w-full rounded-xl border-black/10 bg-white/70 focus:border-brand-primary focus:ring-brand-primary">

            {{-- QTY --}}
            <div class="flex items-center gap-2">
              <div class="text-sm font-semibold">Qty</div>
              <input type="number" min="0" max="99" value="0" name="qty[{{ $p->id }}]"
                     class="w-24 rounded-xl border-black/10 bg-white/70 focus:border-brand-primary focus:ring-brand-primary">
              <div class="text-xs opacity-70">Isi 0 jika tidak pesan.</div>
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
          Tidak ada menu.
        </div>
      @endforelse
    </div>

    <div class="mt-5">
      <button class="w-full h-12 rounded-2xl bg-brand-primary text-brand-surface font-bold text-lg">
        Checkout Sekarang
      </button>
      <div class="text-xs opacity-70 mt-2">
        Tips: Isi qty pada item yang ingin dibeli, lalu tekan Checkout.
      </div>
    </div>
  </div>
</form>
</x-customer-layout>
