<x-seller-layout title="Products - FOUR">
@php
  $imgUrl = function (?string $path) {
      if (empty($path)) return null;

      $path = ltrim($path, '/');

      if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
          return $path;
      }

      if (str_starts_with($path, 'storage/')) {
          return url($path);
      }

      return asset($path);
  };
@endphp

<div class="flex flex-col gap-3 mb-4">
    <div class="flex items-center justify-between">
        <div class="text-xl font-bold">Products</div>
        <a href="{{ route('seller.products.create') }}"
           class="px-4 py-2 rounded-xl bg-brand-primary text-brand-surface font-semibold">
            + Tambah Produk
        </a>
    </div>

    <form method="GET" action="{{ route('seller.products.index') }}" class="flex flex-col sm:flex-row gap-2">
        <input name="q" value="{{ $q }}"
               placeholder="Cari produk..."
               class="w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">

        <select name="filter"
                class="rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            <option value="all" @selected($filter==='all')>Semua</option>
            <option value="available" @selected($filter==='available')>Tersedia</option>
            <option value="out" @selected($filter==='out')>Stok Habis</option>
        </select>

        <button class="px-4 py-2 rounded-xl bg-brand-primary text-brand-surface font-semibold">
            Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
@forelse($products as $p)
    <div class="rounded-2xl bg-white/70 border border-black/10 p-4 flex gap-3">
        <div class="h-20 w-20 rounded-xl overflow-hidden bg-brand-primary/10">
            @php $url = $imgUrl($p->image_path); @endphp

            @if($url)
                <img src="{{ $url }}"
                     class="h-full w-full object-cover"
                     alt="{{ $p->name }}"
                     onerror="this.style.display='none';"
                >
            @else
                <div class="h-full w-full grid place-items-center font-bold text-brand-primary">
                    {{ mb_substr($p->name, 0, 1) }}
                </div>
            @endif
        </div>

        <div class="flex-1">
            <div class="font-semibold">{{ $p->name }}</div>
            <div class="text-sm opacity-80">
                {{ \App\Support\Money::rupiah($p->price) }}
            </div>
            <div class="text-sm mt-1">
                Stock: <b>{{ $p->stock }}</b>
            </div>

            <div class="mt-2 flex gap-2">
                <a href="{{ route('seller.products.edit', $p) }}"
                   class="px-3 py-1 rounded-lg border">Edit</a>

                <form method="POST" action="{{ route('seller.products.destroy', $p) }}"
                      onsubmit="return confirm('Hapus produk?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 rounded-lg bg-red-600 text-white">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="opacity-70">Belum ada produk.</div>
@endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
</x-seller-layout>
