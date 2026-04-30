<x-layouts::app :title="__('Edit Kategori')">
<div class="max-w-xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Edit Kategori</flux:heading>
            <flux:subheading>Perbarui nama kategori: <strong>{{ $kategori->nama }}</strong></flux:subheading>
        </div>
        <flux:button href="{{ route('kategori.index') }}" variant="ghost" icon="chevron-left">
            Kembali
        </flux:button>
    </div>

    <flux:separator />

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600 dark:text-red-300">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">🏷️ Informasi Kategori</flux:heading>
            <flux:separator variant="subtle" />

            <flux:input
                label="Nama Kategori"
                name="nama"
                value="{{ old('nama', $kategori->nama) }}"
                placeholder="Contoh: Pelajaran, Fiksi, Sains..."
                required
                autofocus
            />

            {{-- Info jumlah buku --}}
            @if($kategori->bukus_count > 0)
                <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                    <span class="text-blue-400 text-lg shrink-0">📚</span>
                    <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                        Kategori ini digunakan oleh <strong>{{ $kategori->bukus_count }} buku</strong>.
                        Perubahan nama akan otomatis berlaku pada semua buku tersebut.
                    </p>
                </div>
            @else
                <div class="flex items-center gap-3 p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700">
                    <span class="text-zinc-400 text-lg shrink-0">📭</span>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Kategori ini belum memiliki buku. Anda bisa menghapusnya jika tidak diperlukan.
                    </p>
                </div>
            @endif
        </div>

        {{-- Info tambahan --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
            <flux:heading size="sm" class="mb-3">📊 Informasi</flux:heading>
            <flux:separator variant="subtle" class="mb-4"/>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Dibuat</p>
                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        {{ $kategori->created_at->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Terakhir Diperbarui</p>
                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        {{ $kategori->updated_at->diffForHumans() }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Total Buku</p>
                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        {{ $kategori->bukus_count }} buku
                    </p>
                </div>
                <div>
                    <p class="text-xs text-zinc-400 uppercase tracking-wider mb-1">Lihat di Katalog</p>
                    @if($kategori->bukus_count > 0)
                        <a href="{{ route('katalog') }}?kategori={{ $kategori->id }}"
                           class="text-sm font-semibold text-blue-500 hover:underline">
                            Lihat Buku →
                        </a>
                    @else
                        <p class="text-sm text-zinc-400">—</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end pt-2">
            <flux:button href="{{ route('kategori.index') }}" variant="ghost">Batal</flux:button>
            <flux:button type="submit" variant="primary" icon="check">Update Kategori</flux:button>
        </div>

    </form>

    {{-- Hapus Kategori --}}
    @if($kategori->bukus_count == 0)
        <div class="p-5 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10">
            <flux:heading size="sm" class="text-red-600 dark:text-red-400 mb-2">⚠️ Zona Berbahaya</flux:heading>
            <p class="text-xs text-red-500 dark:text-red-400 mb-4">
                Kategori ini tidak memiliki buku sehingga dapat dihapus. Tindakan ini tidak bisa dibatalkan.
            </p>
            <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                  onsubmit="return confirm('Hapus kategori \'{{ addslashes($kategori->nama) }}\'? Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="danger" icon="trash" size="sm">
                    Hapus Kategori Ini
                </flux:button>
            </form>
        </div>
    @endif

</div>
</x-layouts::app>