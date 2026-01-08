<x-customer-layout title="Panduan - FOUR">
  <div class="mx-auto max-w-3xl">
    <div class="rounded-2xl bg-white/70 border border-black/10 p-6">
      <div class="text-2xl font-bold mb-4 text-brand-primary">Panduan Aplikasi</div>

      <div class="space-y-4 text-sm leading-relaxed">
        <div>
          <div class="font-semibold">1. Cara Pesan</div>
          <ul class="list-disc ml-5 opacity-80">
            <li>Buka halaman Menu.</li>
            <li>Pilih add-ons (opsional) dan isi catatan (opsional).</li>
            <li>Isi Qty lalu tekan <b>Tambah ke Keranjang</b>.</li>
            <li>Buka <b>Keranjang</b> lalu tekan <b>Checkout</b>.</li>
          </ul>
        </div>

        <div>
          <div class="font-semibold">2. Keranjang</div>
          <ul class="list-disc ml-5 opacity-80">
            <li>Kamu bisa update Qty atau hapus item.</li>
            <li>Badge merah di tombol Keranjang berarti masih ada item yang belum checkout.</li>
          </ul>
        </div>

        <div>
          <div class="font-semibold">3. Pembayaran</div>
          <ul class="list-disc ml-5 opacity-80">
            <li>Setelah checkout, sistem membuat invoice.</li>
            <li>Bayar di kasir dengan menunjukkan kode invoice.</li>
          </ul>
        </div>
      </div>

      <div class="mt-6 flex gap-2">
        <a href="{{ route('customer.menu') }}" class="px-4 py-3 rounded-xl bg-brand-primary text-brand-surface font-semibold">
          Kembali ke Menu
        </a>
        <a href="{{ route('customer.cart.show') }}" class="px-4 py-3 rounded-xl border border-black/10 bg-white/60">
          Buka Keranjang
        </a>
      </div>
    </div>
  </div>
</x-customer-layout>
