<x-layouts::app :title="__('Katalog Buku')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Katalog Buku</flux:heading>
            <flux:subheading>Kelola koleksi buku perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('katalog.create') }}">
            Tambah Buku
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

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('katalog') }}" class="flex flex-wrap gap-3 items-end">

        {{-- Search --}}
        <div class="flex-1 min-w-[200px]">
            <flux:input
                name="search"
                icon="magnifying-glass"
                placeholder="Cari judul, pengarang, atau ISBN..."
                value="{{ request('search') }}"
                clearable
            />
        </div>

        {{-- Filter Kategori --}}
        <div class="w-full md:w-48">
            <flux:select name="kategori" placeholder="Semua Kategori">
                <flux:select.option value="">Semua Kategori</flux:select.option>
                @foreach($kategoris as $k)
                    <flux:select.option value="{{ $k->id }}" :selected="request('kategori') == $k->id">
                        {{ $k->nama }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        {{-- Filter Status --}}
        <div class="w-full md:w-40">
            <flux:select name="status" placeholder="Semua Status">
                <flux:select.option value="">Semua Status</flux:select.option>
                <flux:select.option value="tersedia" :selected="request('status') == 'tersedia'">Tersedia</flux:select.option>
                <flux:select.option value="terbatas" :selected="request('status') == 'terbatas'">Terbatas</flux:select.option>
                <flux:select.option value="habis"    :selected="request('status') == 'habis'">Habis</flux:select.option>
            </flux:select>
        </div>

        <flux:button type="submit" variant="primary" icon="magnifying-glass">Cari</flux:button>

        @if(request()->hasAny(['search', 'kategori', 'status']))
            <flux:button href="{{ route('katalog') }}" variant="ghost" icon="x-mark">Reset</flux:button>
        @endif

    </form>

    {{-- Tabel Buku --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-10">No</flux:table.column>
            <flux:table.column class="w-14">Sampul</flux:table.column>
            <flux:table.column>Judul Buku</flux:table.column>
            <flux:table.column>Pengarang</flux:table.column>
            <flux:table.column>Kategori</flux:table.column>
            <flux:table.column class="text-center">Stok</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($data_buku as $index => $buku)
            <flux:table.row :key="$buku->id">

                {{-- No --}}
                <flux:table.cell class="text-zinc-400 text-sm">
                    {{ $data_buku->firstItem() + $index }}
                </flux:table.cell>

                {{-- Sampul --}}
                <flux:table.cell>
                    @if($buku->sampul)
                        <img src="{{ asset('storage/' . $buku->sampul) }}"
                             alt="Sampul {{ $buku->judul }}"
                             class="w-9 h-12 object-cover rounded-md border border-zinc-200 dark:border-zinc-700">
                    @else
                        <div class="w-9 h-12 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 flex items-center justify-content-center text-lg">
                            📖
                        </div>
                    @endif
                </flux:table.cell>

                {{-- Judul --}}
                <flux:table.cell>
                    <div class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">{{ $buku->judul }}</div>
                    <div class="text-xs text-zinc-400 mt-0.5">
                        ISBN: {{ $buku->isbn ?? '—' }}
                        @if($buku->tahun_terbit) &bull; {{ $buku->tahun_terbit }} @endif
                    </div>
                </flux:table.cell>

                {{-- Pengarang --}}
                <flux:table.cell>
                    <div class="text-sm text-zinc-700 dark:text-zinc-300">{{ $buku->pengarang }}</div>
                    @if($buku->penerbit)
                        <div class="text-xs text-zinc-400">{{ $buku->penerbit }}</div>
                    @endif
                </flux:table.cell>

                {{-- Kategori --}}
                <flux:table.cell>
                    <flux:badge variant="subtle" color="zinc">
                        {{ $buku->kategori->nama ?? '—' }}
                    </flux:badge>
                </flux:table.cell>

                {{-- Stok --}}
                <flux:table.cell class="text-center">
                    <span class="font-bold text-base
                        {{ $buku->stok == 0 ? 'text-red-500' : ($buku->stok <= 5 ? 'text-yellow-500' : 'text-green-600') }}">
                        {{ $buku->stok }}
                    </span>
                </flux:table.cell>

                {{-- Status --}}
                <flux:table.cell>
                    @if($buku->stok > 5)
                        <flux:badge color="green">Tersedia</flux:badge>
                    @elseif($buku->stok > 0)
                        <flux:badge color="yellow">Terbatas</flux:badge>
                    @else
                        <flux:badge color="red">Habis</flux:badge>
                    @endif
                </flux:table.cell>

                {{-- Aksi --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end gap-2">
                        <flux:button
                            variant="ghost" size="sm" icon="eye"
                            href="{{ route('katalog.show', $buku->id) }}"
                            title="Lihat Detail"
                        />
                        <flux:button
                            variant="ghost" size="sm" icon="pencil-square"
                            href="{{ route('katalog.edit', $buku->id) }}"
                            title="Edit"
                        />
                        <form action="{{ route('katalog.destroy', $buku->id) }}" method="POST"
                              onsubmit="return confirm('Hapus buku \'{{ addslashes($buku->judul) }}\'? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="ghost" size="sm" icon="trash" title="Hapus" />
                        </form>
                    </div>
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="8" class="text-center py-12">
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <span class="text-4xl">📭</span>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Tidak ada data buku</p>
                        <p class="text-sm">
                            @if(request()->hasAny(['search','kategori','status']))
                                Tidak ada buku yang cocok dengan filter.
                                <a href="{{ route('katalog') }}" class="text-blue-500 hover:underline">Reset filter</a>
                            @else
                                Mulai tambahkan koleksi buku perpustakaan.
                            @endif
                        </p>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    @if($data_buku->hasPages())
        <div>{{ $data_buku->links() }}</div>
    @endif

    {{-- Info total --}}
    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $data_buku->firstItem() ?? 0 }}–{{ $data_buku->lastItem() ?? 0 }}
        dari {{ $data_buku->total() }} buku
    </p>

</div>
</x-layouts::app>