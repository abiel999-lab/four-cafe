<x-seller-layout title="Edit Produk - FOUR">
<div class="text-xl font-bold mb-4">Edit Produk</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-4">
    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @csrf @method('PUT')

        <div class="flex flex-col gap-3">
            <div class="flex gap-3 items-center">
                <div class="h-20 w-20 rounded-2xl overflow-hidden bg-brand-primary/10 border border-black/10 shrink-0">
                    @if($product->image_path)
                        <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full grid place-items-center text-brand-primary font-extrabold text-xl">
                            {{ mb_substr($product->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="text-sm opacity-80">
                    Upload gambar baru jika ingin mengganti.
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold">Kategori</label>
                <select name="category_id" required class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id)==$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Nama Produk</label>
                <input name="name" required maxlength="120" value="{{ old('name', $product->name) }}"
                       class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            </div>

            <div>
                <label class="text-sm font-semibold">Deskripsi</label>
                <textarea name="description" rows="3" maxlength="500"
                          class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-sm font-semibold">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div>
                    <label class="text-sm font-semibold">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-sm font-semibold">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" min="0" max="9999"
                           class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" class="rounded border-black/20" @checked(old('is_available', $product->is_available))>
                        <span class="font-semibold">Tersedia</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold">Gambar Produk (opsional)</label>
                <input type="file" name="image" accept="image/*"
                       class="mt-1 w-full rounded-xl border-black/10 bg-white/60">
            </div>

            <div class="flex gap-2">
                <button class="h-12 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">Update</button>
                <a href="{{ route('seller.products.index') }}" class="h-12 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                    Kembali
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white/60 p-4">
            <div class="font-semibold mb-2">Add-ons / Options untuk produk ini</div>
            @if($options->isEmpty())
                <div class="opacity-80 text-sm">Belum ada options.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($options as $opt)
                        <label class="rounded-xl border border-black/10 bg-white/70 p-3 flex items-center gap-2">
                            <input type="checkbox" name="option_ids[]" value="{{ $opt->id }}" class="rounded border-black/20"
                                   @checked(in_array($opt->id, $selected ?? [], true))>
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
