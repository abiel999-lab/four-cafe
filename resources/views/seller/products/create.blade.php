<x-seller-layout title="Tambah Produk - FOUR">
<div class="text-xl font-bold mb-4">Tambah Produk</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-4">
    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @csrf

        <div class="flex flex-col gap-3">
            <div>
                <label class="text-sm font-semibold">Kategori</label>
                <select name="category_id" required class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold">Nama Produk</label>
                <input name="name" required maxlength="120"
                       class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                @error('name') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold">Deskripsi</label>
                <textarea name="description" rows="3" maxlength="500"
                          class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-sm font-semibold">Harga (Rp)</label>
                    <input type="number" name="price" value="0" min="0" required
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                    @error('price') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold">Stok</label>
                    <input type="number" name="stock" value="0" min="0" required
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                    @error('stock') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-sm font-semibold">Sort Order</label>
                    <input type="number" name="sort_order" value="0" min="0" max="9999"
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" checked class="rounded border-black/20">
                        <span class="font-semibold">Tersedia</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                       class="mt-1 w-full rounded-xl border-black/10 bg-white/60">
                @error('image') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-2">
                <button class="h-12 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">Simpan</button>
                <a href="{{ route('seller.products.index') }}" class="h-12 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                    Batal
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white/60 p-4">
            <div class="font-semibold mb-2">Add-ons / Options untuk produk ini</div>
            @if($options->isEmpty())
                <div class="opacity-80 text-sm">Belum ada options. Tambahkan di menu Options dulu.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($options as $opt)
                        <label class="rounded-xl border border-black/10 bg-white/70 p-3 flex items-center gap-2">
                            <input type="checkbox" name="option_ids[]" value="{{ $opt->id }}" class="rounded border-black/20">
                            <div>
                                <div class="font-semibold text-sm">{{ $opt->name }}</div>
                                <div class="text-xs opacity-80">+Rp {{ number_format($opt->price, 0, ',', '.') }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
    </form>
</div>
</x-seller-layout>
