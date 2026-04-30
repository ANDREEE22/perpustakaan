<x-layouts::app :title="__('Detail Buku')">
<div class="max-w-5xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800">
                <flux:icon.book-open class="text-amber-600 dark:text-amber-400" />
            </div>
            <div>
                <flux:heading size="xl" level="1">{{ $buku->judul }}</flux:heading>
                <flux:subheading>ISBN: {{ $buku->isbn ?? '—' }} &bull; Katalog Perpustakaan</flux:subheading>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <flux:button variant="primary" icon="pencil-square" href="{{ route('katalog.edit', $buku->id) }}">
                Edit Data
            </flux:button>
            <flux:button variant="ghost" icon="chevron-left" href="{{ route('katalog') }}">
                Kembali
            </flux:button>
        </div>
    </div>

    <flux:separator />

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Konten Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Lengkap --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            <flux:card>
                <div class="space-y-6">

                    {{-- Sampul + Identitas --}}
                    <div class="flex gap-5 items-start">
                        <div class="shrink-0">
                            @if($buku->sampul)
                                <img src="{{ asset('storage/' . $buku->sampul) }}"
                                     alt="Sampul {{ $buku->judul }}"
                                     class="w-24 h-32 object-cover rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">
                            @else
                                <div class="w-24 h-32 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 flex items-center justify-center text-4xl">
                                    📖
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-zinc-800 dark:text-zinc-100">{{ $buku->judul }}</h2>
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">oleh {{ $buku->pengarang }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <flux:badge variant="subtle" color="zinc">{{ $buku->kategori->nama ?? '—' }}</flux:badge>
                                @if($buku->stok > 5)
                                    <flux:badge color="green">✅ Tersedia</flux:badge>
                                @elseif($buku->stok > 0)
                                    <flux:badge color="yellow">⚠️ Terbatas</flux:badge>
                                @else
                                    <flux:badge color="red">❌ Habis</flux:badge>
                                @endif
                            </div>
                        </div>
                    </div>

                    <flux:separator variant="subtle" />

                    {{-- Sinopsis --}}
                    <div>
                        <flux:label class="text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-2">
                            Sinopsis / Deskripsi
                        </flux:label>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-sm text-justify">
                            {{ $buku->description ?: 'Tidak ada deskripsi untuk buku ini.' }}
                        </p>
                    </div>

                    <flux:separator variant="subtle" />

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">Pengarang</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">{{ $buku->pengarang }}</p>
                        </div>
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">Penerbit</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">{{ $buku->penerbit ?: '—' }}</p>
                        </div>
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">Tahun Terbit</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">{{ $buku->tahun_terbit ?: '—' }}</p>
                        </div>
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">ISBN</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">{{ $buku->isbn ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- Hapus buku --}}
            <div class="flex justify-start">
                <form action="{{ route('katalog.destroy', $buku->id) }}" method="POST"
                      onsubmit="return confirm('Hapus buku \'{{ addslashes($buku->judul) }}\'? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1.5 transition-all opacity-60 hover:opacity-100">
                        <flux:icon.trash variant="mini" />
                        Hapus buku dari database
                    </button>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Inventaris --}}
        <div class="flex flex-col gap-5">

            {{-- Status Inventaris --}}
            <flux:card class="bg-zinc-50 dark:bg-white/[0.02] border-dashed">
                <div class="space-y-5">
                    <flux:heading size="sm">Status Inventaris</flux:heading>

                    <div class="flex items-center justify-between">
                        <flux:label>Ketersediaan</flux:label>
                        @if($buku->stok > 5)
                            <flux:badge color="green" size="sm">Tersedia</flux:badge>
                        @elseif($buku->stok > 0)
                            <flux:badge color="yellow" size="sm">Terbatas</flux:badge>
                        @else
                            <flux:badge color="red" size="sm">Habis</flux:badge>
                        @endif
                    </div>

                    <div class="flex items-center justify-between border-y border-zinc-200 dark:border-zinc-800 py-4">
                        <flux:label>Jumlah Stok</flux:label>
                        <span class="text-2xl font-black text-zinc-800 dark:text-white">
                            {{ $buku->stok }}
                            <span class="text-xs font-normal text-zinc-500 uppercase">Eks.</span>
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <flux:icon.map-pin variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Lokasi Rak</flux:label>
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $buku->lokasi_rak ?: 'Belum diatur' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon.clock variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Terakhir Diperbarui</flux:label>
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $buku->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon.calendar variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Ditambahkan</flux:label>
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $buku->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- Info box --}}
            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                <div class="flex gap-3">
                    <flux:icon.information-circle class="text-blue-400 shrink-0" />
                    <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                        Pastikan lokasi rak diisi dengan benar agar petugas dan siswa mudah menemukan buku ini di rak.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
</x-layouts::app>