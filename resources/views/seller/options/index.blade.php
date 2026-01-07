<x-seller-layout title="Options - FOUR">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div class="text-xl font-bold">Add-ons / Options</div>
    <a href="{{ route('seller.options.create') }}"
       class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold grid place-items-center">
        + Tambah Option
    </a>
</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-2 sm:p-4">
    <div class="overflow-x-auto">
        <table class="min-w-[720px] w-full text-sm">
            <thead class="bg-black/5">
                <tr class="text-left">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Harga</th>
                    <th class="p-3">Aktif</th>
                    <th class="p-3 w-56">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($options as $opt)
                    <tr class="border-t border-black/10">
                        <td class="p-3 font-semibold">{{ $opt->name }}</td>
                        <td class="p-3">Rp {{ number_format($opt->price, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-lg text-xs {{ $opt->is_active ? 'bg-green-100 text-green-900' : 'bg-gray-200 text-gray-900' }}">
                                {{ $opt->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('seller.options.edit', $opt) }}"
                                   class="h-10 px-3 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('seller.options.destroy', $opt) }}"
                                      onsubmit="return confirm('Hapus option ini?')">
                                    @csrf @method('DELETE')
                                    <button class="h-10 px-3 rounded-xl bg-red-600 text-white">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-6 opacity-80" colspan="4">Belum ada option.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $options->links() }}
    </div>
</div>
</x-seller-layout>
