<x-seller-layout title="Panduan Penggunaan">
    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold mb-2">Panduan Penggunaan Aplikasi</h1>
        <p class="text-gray-600 mb-6">
            Halaman ini berisi panduan lengkap cara menggunakan aplikasi
            <strong>FOUR Cafe & Coffee</strong>.
            Cocok untuk kasir, admin, maupun pemilik warung.
        </p>

        <div class="rounded-xl border bg-white p-6 space-y-4">
            <h2 class="text-lg font-semibold">📘 User Manual (PDF)</h2>
            <p class="text-sm text-gray-600">
                File PDF berisi panduan lengkap mulai dari:
            </p>

            <ul class=" ml-5 text-sm text-gray-700 space-y-1">
                <li>Cara menerima pesanan customer</li>
                <li>Cara mengelola produk & stok</li>
                <li>Cara cek order & pembayaran</li>
                <li>Cara melihat laporan penjualan</li>
                <li>Penjelasan setiap menu di aplikasi</li>
            </ul>

            <a
                href="{{ asset('manual/FOUR-User-Manual.pdf') }}"
                target="_blank"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl
                       bg-brand-primary text-brand-surface font-semibold
                       hover:opacity-90"
            >
                ⬇ Download Panduan (PDF)
            </a>
        </div>
    </div>
</x-seller-layout>
