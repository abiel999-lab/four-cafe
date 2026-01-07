<x-seller-layout title="Reports - FOUR">
@php
  $range = $range ?? 'today';

  $bestLabels = $productAgg->pluck('name')->values();
  $bestQty    = $productAgg->pluck('qty')->map(fn($v)=>(int)$v)->values();
  $bestRev    = $productAgg->pluck('revenue')->map(fn($v)=>(int)$v)->values();

  $revLabels  = $revenueByDate->pluck('d')->values();
  $revValues  = $revenueByDate->pluck('revenue')->map(fn($v)=>(int)$v)->values();
@endphp

<div class="flex flex-col gap-4">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <div class="text-xl font-bold">Reports</div>
      <div class="text-sm opacity-70">Analisis penjualan berdasarkan pesanan yang sudah dibayar</div>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('seller.reports.index', ['range'=>'today']) }}"
         class="px-4 h-11 rounded-xl border border-black/10 grid place-items-center {{ $range==='today' ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
        Hari Ini
      </a>
      <a href="{{ route('seller.reports.index', ['range'=>'month']) }}"
         class="px-4 h-11 rounded-xl border border-black/10 grid place-items-center {{ $range==='month' ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
        Bulan Ini
      </a>
      <a href="{{ route('seller.reports.index', ['range'=>'year']) }}"
         class="px-4 h-11 rounded-xl border border-black/10 grid place-items-center {{ $range==='year' ? 'bg-brand-primary text-brand-surface' : 'bg-white/60' }}">
        Tahun Ini
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
    <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
      <div class="font-semibold mb-2">Best Seller (Qty)</div>
      <div class="h-[280px]">
        <canvas id="bestSellerChart"></canvas>
      </div>
    </div>

    <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
      <div class="font-semibold mb-2">Revenue</div>
      <div class="h-[280px]">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>

  <div class="rounded-2xl bg-white/70 border border-black/10 p-4">
    <div class="font-semibold mb-3">Ringkasan Produk</div>

    <div class="overflow-x-auto">
      <table class="min-w-[720px] w-full text-sm">
        <thead class="bg-black/5">
          <tr class="text-left">
            <th class="p-3 w-[50%]">Produk</th>
            <th class="p-3 w-[15%]">Qty</th>
            <th class="p-3 w-[20%] text-right">Revenue</th>
          </tr>
        </thead>
        <tbody>
          @forelse($productAgg as $row)
            <tr class="border-t border-black/10">
              <td class="p-3 font-semibold">{{ $row->name }}</td>
              <td class="p-3">{{ (int)$row->qty }}</td>
              <td class="p-3 text-right font-semibold">{{ \App\Support\Money::rupiah((int)$row->revenue) }}</td>
            </tr>
          @empty
            <tr class="border-t border-black/10">
              <td class="p-3 opacity-70" colspan="3">Belum ada data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const bestLabels = @json($bestLabels);
  const bestQty = @json($bestQty);

  const revLabels = @json($revLabels);
  const revValues = @json($revValues);

  // Best seller
  new Chart(document.getElementById('bestSellerChart'), {
    type: 'bar',
    data: {
      labels: bestLabels,
      datasets: [{
        label: 'Qty', // <-- FIX undefined
        data: bestQty,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Revenue
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: revLabels,
      datasets: [{
        label: 'Revenue (Rp)', // <-- FIX undefined
        data: revValues,
        tension: 0.25,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true } }
    }
  });
</script>
</x-seller-layout>
