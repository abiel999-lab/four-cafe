<x-seller-layout title="Panduan Penggunaan">
    <div class="max-w-4xl space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Panduan Penggunaan Aplikasi</h1>
            <p class="text-brand-dark/70 mt-1">
                Panduan lengkap penggunaan <strong>FOUR Cafe & Coffee</strong> untuk kasir, admin, dan pemilik.
                Di sini kamu bisa belajar semua fitur tanpa harus buka PDF.
            </p>
        </div>

        {{-- Quick Actions --}}
        <div class="rounded-2xl border border-black/10 bg-white/70 p-4 sm:p-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div>
                <div class="font-semibold">Butuh versi PDF?</div>
                <div class="text-sm text-brand-dark/70">Opsional — tetap ada tombol download kalau kamu mau simpan/print.</div>
            </div>

            <a
                href="{{ asset('manual/FOUR-User-Manual.pdf') }}"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-brand-primary text-brand-surface font-semibold hover:opacity-90"
            >
                ⬇ Download PDF
            </a>
        </div>

        {{-- Table of Contents --}}
        <div class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6">
            <h2 class="text-lg font-semibold mb-3">Daftar Isi</h2>
            <div class="grid sm:grid-cols-2 gap-2 text-sm">
                <a href="#konsep" class="underline text-brand-primary hover:opacity-80">1. Konsep Aplikasi & Alur Kerja</a>
                <a href="#customer" class="underline text-brand-primary hover:opacity-80">2. Cara Customer Memesan</a>
                <a href="#kasir" class="underline text-brand-primary hover:opacity-80">3. Cara Kasir Memproses Pesanan</a>
                <a href="#orders" class="underline text-brand-primary hover:opacity-80">4. Menu Orders (Seller)</a>
                <a href="#detail-order" class="underline text-brand-primary hover:opacity-80">5. Order Detail</a>
                <a href="#invoice" class="underline text-brand-primary hover:opacity-80">6. Invoice & Print Struk</a>
                <a href="#products" class="underline text-brand-primary hover:opacity-80">7. Products & Stok</a>
                <a href="#categories" class="underline text-brand-primary hover:opacity-80">8. Categories</a>
                <a href="#options" class="underline text-brand-primary hover:opacity-80">9. Options / Add-ons</a>
                <a href="#reports" class="underline text-brand-primary hover:opacity-80">10. Reports & Export</a>
                <a href="#settings" class="underline text-brand-primary hover:opacity-80">11. Settings</a>
                <a href="#operasional" class="underline text-brand-primary hover:opacity-80">12. SOP Operasional Harian</a>
                <a href="#troubleshoot" class="underline text-brand-primary hover:opacity-80">13. Troubleshooting</a>
            </div>
        </div>

        {{-- 1) Konsep --}}
        <div id="konsep" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">1. Konsep Aplikasi & Alur Kerja</h2>

            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>
                    Aplikasi ini punya 2 sisi:
                </p>
                <ul class="list-disc ml-5 space-y-1">
                    <li><strong>Customer Web</strong> → untuk pelanggan memilih menu dan checkout.</li>
                    <li><strong>Seller Web</strong> → untuk kasir/admin menerima order, tandai bayar, kelola produk, dan laporan.</li>
                </ul>

                <p class="mt-2">
                    Alur kerja normal:
                </p>
                <ol class="list-decimal ml-5 space-y-1">
                    <li>Customer buka menu → pilih item → checkout → dapat kode invoice.</li>
                    <li>Kasir buka Seller → cek order masuk → tandai sudah bayar → proses status (diproses/selesai).</li>
                    <li>Stok produk otomatis berkurang setelah checkout (atau setelah pembayaran, tergantung implementasi sistem).</li>
                    <li>Owner lihat laporan di Reports dan export bila perlu.</li>
                </ol>
            </div>
        </div>

        {{-- 2) Customer --}}
        <div id="customer" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">2. Cara Customer Memesan</h2>

            <div class="text-sm text-brand-dark/80 space-y-3">
                <p>
                    Customer cukup melakukan langkah berikut:
                </p>

                <ol class="list-decimal ml-5 space-y-2">
                    <li>
                        <strong>Buka Halaman Menu</strong><br>
                        Customer akan melihat daftar produk (Coffee, Snacks, dll).
                    </li>
                    <li>
                        <strong>Pilih Produk</strong><br>
                        Customer memilih jumlah (qty) dan add-ons (jika ada).
                    </li>
                    <li>
                        <strong>Checkout Sekali</strong><br>
                        Customer menekan tombol checkout untuk menyelesaikan semua pesanan sekaligus.
                        (Tidak perlu klik tambah berulang kali per item.)
                    </li>
                    <li>
                        <strong>Dapat Invoice</strong><br>
                        Setelah checkout, customer mendapatkan kode invoice untuk ditunjukkan ke kasir.
                    </li>
                </ol>

                <div class="rounded-xl bg-brand-surface p-4 border border-black/10">
                    <div class="font-semibold">Catatan penting</div>
                    <ul class="list-disc ml-5 mt-1 space-y-1">
                        <li>Jika stok habis, produk seharusnya tidak bisa dipesan.</li>
                        <li>Harga total dihitung otomatis dari harga produk + add-ons.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 3) Kasir --}}
        <div id="kasir" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">3. Cara Kasir Memproses Pesanan</h2>

            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Langkah kasir saat ada pelanggan:</p>
                <ol class="list-decimal ml-5 space-y-2">
                    <li>
                        <strong>Customer menunjukkan kode invoice</strong> ke kasir.
                    </li>
                    <li>
                        Kasir buka menu <strong>Orders</strong> lalu cari berdasarkan:
                        <ul class="list-disc ml-5 mt-1">
                            <li>Nama customer</li>
                            <li>Kode invoice</li>
                        </ul>
                    </li>
                    <li>
                        Buka <strong>Order Detail</strong> untuk melihat item, add-ons, catatan, dan total.
                    </li>
                    <li>
                        Jika sudah bayar, tekan tombol <strong>Tandai Sudah Bayar</strong>.
                    </li>
                    <li>
                        Update status sesuai alur:
                        <strong>Belum Bayar → Diproses → Siap → Selesai</strong>.
                    </li>
                </ol>
            </div>
        </div>

        {{-- 4) Orders --}}
        <div id="orders" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">4. Menu Orders (Seller)</h2>

            <div class="text-sm text-brand-dark/80 space-y-3">
                <p>Di halaman Orders kamu bisa:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Melihat semua pesanan masuk.</li>
                    <li>Filter berdasarkan status (Belum Bayar, Sudah Bayar, Diproses, Siap, Selesai, Batal, All).</li>
                    <li>Mencari order berdasarkan nama atau kode invoice.</li>
                </ul>

                <div class="rounded-xl border border-black/10 bg-brand-surface p-4">
                    <div class="font-semibold mb-1">Status umum</div>
                    <ul class="list-disc ml-5 space-y-1">
                        <li><strong>Belum Bayar</strong> → order baru dibuat, customer belum bayar.</li>
                        <li><strong>Sudah Bayar</strong> → kasir sudah menandai pembayaran.</li>
                        <li><strong>Diproses</strong> → pesanan sedang dibuat.</li>
                        <li><strong>Siap</strong> → pesanan sudah siap diambil.</li>
                        <li><strong>Selesai</strong> → pesanan selesai diserahkan.</li>
                        <li><strong>Batal</strong> → order dibatalkan.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 5) Order Detail --}}
        <div id="detail-order" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">5. Order Detail</h2>

            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Di halaman detail, kamu akan melihat:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Informasi customer & kode invoice</li>
                    <li>Status dan payment</li>
                    <li>Daftar item (qty, harga, add-ons, catatan)</li>
                    <li><strong>Grand Total</strong></li>
                </ul>

                <p class="mt-2">
                    Tindakan utama:
                </p>
                <ul class="list-disc ml-5 space-y-1">
                    <li><strong>Tandai Sudah Bayar</strong> / Batalkan Paid</li>
                    <li><strong>Update Status</strong> (Diproses, Siap, Selesai, dll)</li>
                    <li><strong>Lihat Invoice</strong></li>
                </ul>
            </div>
        </div>

        {{-- 6) Invoice --}}
        <div id="invoice" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">6. Invoice & Print Struk</h2>

            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>
                    Invoice adalah ringkasan order yang biasanya digunakan untuk:
                </p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Verifikasi pesanan</li>
                    <li>Print struk kasir</li>
                    <li>Bukti pembayaran</li>
                </ul>

                <p class="mt-2">
                    Jika fitur print struk aktif, kasir cukup klik tombol <strong>Print</strong> pada halaman invoice,
                    lalu printer akan mencetak struk (format profesional).
                </p>
            </div>
        </div>

        {{-- 7) Products --}}
        <div id="products" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">7. Products & Stok</h2>

            <div class="text-sm text-brand-dark/80 space-y-3">
                <p>Di halaman Products kamu bisa:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Tambah produk baru</li>
                    <li>Edit nama/harga/stok</li>
                    <li>Hapus produk</li>
                </ul>

                <div class="rounded-xl border border-black/10 bg-brand-surface p-4">
                    <div class="font-semibold">Cara kerja stok (yang ideal)</div>
                    <ul class="list-disc ml-5 mt-1 space-y-1">
                        <li>Jika customer checkout, stok otomatis berkurang sesuai qty yang dipesan.</li>
                        <li>Jika order dibatalkan, stok kembali (opsional jika kamu aktifkan).</li>
                        <li>Jika stok 0, produk seharusnya tidak bisa dipesan.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 8) Categories --}}
        <div id="categories" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-2">
            <h2 class="text-xl font-bold">8. Categories</h2>
            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Kategori digunakan agar menu rapi (misal Coffee / Snacks / Makanan).</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Tambah kategori</li>
                    <li>Edit kategori</li>
                    <li>Hapus kategori (hati-hati jika masih dipakai produk)</li>
                </ul>
            </div>
        </div>

        {{-- 9) Options --}}
        <div id="options" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-2">
            <h2 class="text-xl font-bold">9. Options / Add-ons</h2>
            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Options dipakai untuk tambahan seperti:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Extra Shot</li>
                    <li>Caramel Syrup</li>
                    <li>Topping, dll</li>
                </ul>
                <p>
                    Saat customer memilih add-ons, total otomatis bertambah.
                </p>
            </div>
        </div>

        {{-- 10) Reports --}}
        <div id="reports" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">10. Reports & Export</h2>

            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Reports digunakan untuk melihat:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Best seller (Qty)</li>
                    <li>Revenue (pendapatan)</li>
                    <li>Ringkasan per produk</li>
                </ul>

                <p class="mt-2">
                    Export digunakan jika owner ingin:
                </p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Menyimpan laporan ke Excel/CSV</li>
                    <li>Rekap bulanan/tahunan</li>
                    <li>Audit sederhana</li>
                </ul>

                <div class="rounded-xl border border-black/10 bg-brand-surface p-4">
                    <div class="font-semibold">Catatan</div>
                    <p class="text-sm text-brand-dark/80">
                        Data laporan biasanya dihitung dari order yang <strong>sudah dibayar</strong>.
                        Jadi jika order belum dibayar, tidak masuk ke laporan.
                    </p>
                </div>
            </div>
        </div>

        {{-- 11) Settings --}}
        <div id="settings" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-2">
            <h2 class="text-xl font-bold">11. Settings</h2>
            <div class="text-sm text-brand-dark/80 space-y-2">
                <p>Settings dipakai untuk mengatur data toko, misalnya:</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Nama toko</li>
                    <li>Alamat (opsional)</li>
                    <li>Catatan pembayaran untuk customer (opsional)</li>
                </ul>
            </div>
        </div>

        {{-- 12) SOP --}}
        <div id="operasional" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">12. SOP Operasional Harian</h2>

            <div class="text-sm text-brand-dark/80 space-y-3">
                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold mb-1">Saat buka warung</div>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Login seller</li>
                        <li>Cek stok produk (Products)</li>
                        <li>Pastikan menu tersedia (Category/Options kalau perlu)</li>
                    </ol>
                </div>

                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold mb-1">Saat ada order masuk</div>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Buka Orders</li>
                        <li>Cari order yang sesuai</li>
                        <li>Jika customer bayar, tandai sudah bayar</li>
                        <li>Update status sampai selesai</li>
                    </ol>
                </div>

                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold mb-1">Saat tutup warung</div>
                    <ol class="list-decimal ml-5 space-y-1">
                        <li>Buka Reports → cek pendapatan hari ini</li>
                        <li>Export laporan (opsional)</li>
                        <li>Logout</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- 13) Troubleshooting --}}
        <div id="troubleshoot" class="rounded-2xl border border-black/10 bg-white p-4 sm:p-6 space-y-3">
            <h2 class="text-xl font-bold">13. Troubleshooting</h2>

            <div class="text-sm text-brand-dark/80 space-y-3">
                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold">A. Total / Grand Total kosong</div>
                    <ul class="list-disc ml-5 mt-1 space-y-1">
                        <li>Pastikan field total di tabel orders terisi (misal: <code>total</code>).</li>
                        <li>Pastikan order_items punya <code>quantity</code> dan <code>line_total</code>.</li>
                        <li>Jika total dihitung dari item, pastikan controller menghitungnya saat checkout.</li>
                    </ul>
                </div>

                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold">B. Stok tidak berkurang setelah checkout</div>
                    <ul class="list-disc ml-5 mt-1 space-y-1">
                        <li>Pastikan logika pengurangan stok berjalan di proses checkout.</li>
                        <li>Pastikan transaksi DB digunakan agar stok dan order tersimpan aman.</li>
                        <li>Jika stok ingin berkurang setelah “paid”, pindahkan logika ke tombol “Tandai Sudah Bayar”.</li>
                    </ul>
                </div>

                <div class="rounded-xl border border-black/10 p-4">
                    <div class="font-semibold">C. Setelah logout malah ke customer menu</div>
                    <ul class="list-disc ml-5 mt-1 space-y-1">
                        <li>Biasanya karena route <code>/</code> mengarah ke customer menu.</li>
                        <li>Solusi: setelah logout, redirect ke halaman login seller (misal route login).</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-seller-layout>
