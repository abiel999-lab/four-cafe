<x-seller-layout title="Categories - FOUR">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div class="text-xl font-bold">Categories</div>
    <a href="{{ route('seller.categories.create') }}"
       class="h-11 px-4 rounded-xl bg-brand-primary text-brand-surface font-semibold grid place-items-center">
        + Tambah Kategori
    </a>
</div>

<div class="rounded-2xl bg-white/70 border border-black/10 p-2 sm:p-4">
    <div class="overflow-x-auto">
        <table class="min-w-[640px] w-full text-sm">
            <thead class="bg-black/5">
                <tr class="text-left">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Sort</th>
                    <th class="p-3 w-56">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr class="border-t border-black/10">
                        <td class="p-3 font-semibold">{{ $cat->name }}</td>
                        <td class="p-3">{{ $cat->sort_order }}</td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('seller.categories.edit', $cat) }}"
                                   class="h-10 px-3 rounded-xl border border-black/10 bg-white/60 grid place-items-center">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('seller.categories.destroy', $cat) }}"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="h-10 px-3 rounded-xl bg-red-600 text-white">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-6 opacity-80" colspan="3">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
</x-seller-layout>
