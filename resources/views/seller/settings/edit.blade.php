<x-seller-layout title="Settings - FOUR">
<div class="text-xl font-bold mb-4">Settings</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-4 sm:p-6 max-w-2xl">
    <form method="POST" action="{{ route('seller.settings.update') }}" class="flex flex-col gap-4">
        @csrf

        <div>
            <label class="text-sm font-semibold">Nama Toko</label>
            <input name="store_name" value="{{ old('store_name', $data['store_name']) }}" required maxlength="100"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
        </div>

        <div>
            <label class="text-sm font-semibold">Alamat</label>
            <input name="store_address" value="{{ old('store_address', $data['store_address']) }}" maxlength="255"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
        </div>

        <div>
            <label class="text-sm font-semibold">Catatan Pembayaran (Customer)</label>
            <input name="payment_note" value="{{ old('payment_note', $data['payment_note']) }}" maxlength="255"
                   class="mt-1 w-full rounded-xl border-black/10 focus:border-brand-primary focus:ring-brand-primary">
            <div class="text-xs opacity-70 mt-1">Contoh: “Pembayaran dilakukan di kasir.”</div>
        </div>

        <button class="h-12 rounded-xl bg-brand-primary text-brand-surface font-semibold">
            Simpan Settings
        </button>
    </form>
</div>
</x-seller-layout>
