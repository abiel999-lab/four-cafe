<x-seller-layout title="Tambah Option - FOUR">
<div class="text-xl font-bold mb-4">Tambah Option</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-4 max-w-xl">
    <form method="POST" action="{{ route('seller.options.store') }}" class="flex flex-col gap-3">
        @csrf

        <div>
            <label class="text-sm font-semibold">Nama Option</label>
            <input name="name" required maxlength="80"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            @error('name') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="text-sm font-semibold">Harga Tambahan (Rp)</label>
            <input type="number" name="price" value="0" min="0"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            @error('price') <div class="text-sm text-red-700 mt-1">{{ $message }}</div> @enderror
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-black/20">
            <span class="font-semibold">Aktif</span>
        </label>

        <div class="flex gap-2">
            <button class="h-12 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold">Simpan</button>
            <a href="{{ route('seller.options.index') }}" class="h-12 px-4 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                Batal
            </a>
        </div>
    </form>
</div>
</x-seller-layout>
