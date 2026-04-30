<x-layouts::app :title="__('Manajemen Kategori')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Manajemen Kategori</flux:heading>
            <flux:subheading>Kelola kategori buku perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('kategori.create') }}">
            Tambah Kategori
        </flux:button>
    </div>

    <flux:separator />

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Info total --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-2xl">🏷️</div>
            <div>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ $kategoris->total() }}</p>
                <p class="text-xs text-zinc-400 uppercase tracking-wide">Total Kategori</p>
            </div>
        </div>
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-2xl">📚</div>
            <div>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ $kategoris->sum('bukus_count') }}</p>
                <p class="text-xs text-zinc-400 uppercase tracking-wide">Total Buku Terkategori</p>
            </div>
        </div>
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-2xl">📭</div>
            <div>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ $kategoris->filter(fn($k) => $k->bukus_count == 0)->count() }}</p>
                <p class="text-xs text-zinc-400 uppercase tracking-wide">Kategori Kosong</p>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-10">No</flux:table.column>
            <flux:table.column>Nama Kategori</flux:table.column>
            <flux:table.column class="text-center">Jumlah Buku</flux:table.column>
            <flux:table.column>Dibuat</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($kategoris as $index => $kategori)
            <flux:table.row :key="$kategori->id">

                {{-- No --}}
                <flux:table.cell class="text-zinc-400 text-sm">
                    {{ $kategoris->firstItem() + $index }}
                </flux:table.cell>

                {{-- Nama --}}
                <flux:table.cell>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-base">🏷️</div>
                        <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">{{ $kategori->nama }}</span>
                    </div>
                </flux:table.cell>

                {{-- Jumlah Buku --}}
                <flux:table.cell class="text-center">
                    @if($kategori->bukus_count > 0)
                        <a href="{{ route('katalog') }}?kategori={{ $kategori->id }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-semibold hover:bg-blue-100 transition-colors">
                            📚 {{ $kategori->bukus_count }} buku
                        </a>
                    @else
                        <span class="text-xs text-zinc-400 italic">Belum ada buku</span>
                    @endif
                </flux:table.cell>

                {{-- Dibuat --}}
                <flux:table.cell>
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $kategori->created_at->format('d M Y') }}
                    </span>
                </flux:table.cell>

                {{-- Aksi --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end gap-2">
                        <flux:button
                            variant="ghost" size="sm" icon="pencil-square"
                            href="{{ route('kategori.edit', $kategori->id) }}"
                            title="Edit Kategori"
                        />
                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori \'{{ addslashes($kategori->nama) }}\'? Pastikan tidak ada buku yang menggunakan kategori ini.')">
                            @csrf
                            @method('DELETE')
                            <flux:button
                                type="submit" variant="ghost" size="sm" icon="trash"
                                title="Hapus Kategori"
                                :disabled="$kategori->bukus_count > 0"
                            />
                        </form>
                    </div>
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-center py-12">
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <span class="text-4xl">🏷️</span>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada kategori</p>
                        <p class="text-sm">
                            Mulai tambahkan kategori untuk mengelompokkan buku.
                        </p>
                        <flux:button variant="primary" icon="plus" href="{{ route('kategori.create') }}" class="mt-2">
                            Tambah Kategori Pertama
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    @if($kategoris->hasPages())
        <div>{{ $kategoris->links() }}</div>
    @endif

    {{-- Info --}}
    <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800">
        <span class="text-amber-500 text-lg shrink-0">⚠️</span>
        <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
            Kategori yang sudah memiliki buku <strong>tidak dapat dihapus</strong>. Pindahkan atau hapus buku terkait terlebih dahulu sebelum menghapus kategori.
        </p>
    </div>

    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $kategoris->firstItem() ?? 0 }}–{{ $kategoris->lastItem() ?? 0 }}
        dari {{ $kategoris->total() }} kategori
    </p>

</div>
</x-layouts::app>