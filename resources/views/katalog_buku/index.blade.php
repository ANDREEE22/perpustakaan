<x-layouts::app :title="__('Katalog Buku')">
<style>
    :root {
        --lib-teal:    #0f766e;
        --lib-emerald: #10b981;
    }
</style>
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Katalog Buku</flux:heading>
            <flux:subheading>Kelola koleksi buku perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('katalog.create') }}" style="background: var(--lib-teal); border:none; color: #fff;">
            Tambah Buku
        </flux:button>
    </div>

    <flux:separator />


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

        <flux:button type="submit" variant="primary" icon="magnifying-glass" style="background: var(--lib-teal); border:none; color: #fff;">Cari</flux:button>

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
                        Kode: {{ $buku->kode_buku ?? '—' }} &bull; ISBN: {{ $buku->isbn ?? '—' }}
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
                        <form action="{{ route('katalog.destroy', $buku->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <flux:button
                                type="button"
                                variant="ghost"
                                size="sm"
                                icon="trash"
                                title="Hapus"
                                onclick="hapusBuku(this.closest('form'), '{{ addslashes($buku->judul) }}')"
                            />
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
        <div>{{ $data_buku->links('vendor.pagination.custom') }}</div>
    @endif

    {{-- Info total --}}
    @php
        $first = ($data_buku->currentPage() - 1) * $data_buku->perPage() + 1;
        $last = min($data_buku->currentPage() * $data_buku->perPage(), $data_buku->total());
    @endphp
    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $first }}–{{ $last }}
        dari {{ $data_buku->total() }} buku
    </p>

</div>

<script>
function hapusBuku(form, judul) {
    Swal.fire({
        title: 'Hapus buku?',
        text: `Yakin ingin menghapus buku "${judul}"? Tindakan ini tidak bisa dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
</x-layouts::app>