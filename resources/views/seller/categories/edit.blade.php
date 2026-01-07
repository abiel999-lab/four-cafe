<x-seller-layout title="Edit Kategori - FOUR">
<div class="text-xl font-bold mb-4">Edit Kategori</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-4 max-w-xl">
    <form method="POST" action="{{ route('seller.categories.update', $category) }}" class="flex flex-col gap-3">
        @csrf @method('PUT')

        <div>
            <label class="text-sm font-semibold">Nama</label>
            <input name="name" required maxlength="80" value="{{ old('name', $category->name) }}"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            @error('name') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="text-sm font-semibold">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" max="9999"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
        </div>

        <div class="flex gap-2">
            <button class="h-12 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">Update</button>
            <a href="{{ route('seller.categories.index') }}" class="h-12 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                Kembali
            </a>
        </div>
    </form>
</div>
</x-seller-layout>
