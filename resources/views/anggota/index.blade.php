<x-layouts::app :title="__('Data Anggota')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Data Anggota</flux:heading>
            <flux:subheading>Kelola data siswa dan guru perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('anggota.create') }}">
            Tambah Anggota
        </flux:button>
    </div>

    <flux:separator />

    {{-- Flash Messages --}}
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
    <form method="GET" action="{{ route('anggota.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <flux:input
                name="search"
                icon="magnifying-glass"
                placeholder="Cari nama atau nomor induk..."
                value="{{ request('search') }}"
                clearable
            />
        </div>

        <div class="w-full md:w-40">
            <flux:select name="tipe" placeholder="Semua Tipe">
                <flux:select.option value="">Semua Tipe</flux:select.option>
                <flux:select.option value="siswa" :selected="request('tipe') == 'siswa'">Siswa</flux:select.option>
                <flux:select.option value="guru"  :selected="request('tipe') == 'guru'">Guru / Staf</flux:select.option>
            </flux:select>
        </div>

        <div class="w-full md:w-44">
            <flux:select name="jenis_kelamin" placeholder="Semua JK">
                <flux:select.option value="">Semua J. Kelamin</flux:select.option>
                <flux:select.option value="L" :selected="request('jenis_kelamin') == 'L'">Laki-laki</flux:select.option>
                <flux:select.option value="P" :selected="request('jenis_kelamin') == 'P'">Perempuan</flux:select.option>
            </flux:select>
        </div>

        <flux:button type="submit" variant="primary" icon="magnifying-glass">Cari</flux:button>

        @if(request()->hasAny(['search', 'tipe', 'jenis_kelamin']))
            <flux:button href="{{ route('anggota.index') }}" variant="ghost" icon="x-mark">Reset</flux:button>
        @endif
    </form>

    {{-- Tabel --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-10">No</flux:table.column>
            <flux:table.column class="w-12">Foto</flux:table.column>
            <flux:table.column>Nomor Induk</flux:table.column>
            <flux:table.column>Nama Lengkap</flux:table.column>
            <flux:table.column>J. Kelamin</flux:table.column>
            <flux:table.column>Kelas / Status</flux:table.column>
            <flux:table.column>No Telepon</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($anggotas as $index => $anggota)
            <flux:table.row :key="$anggota->id">

                {{-- No --}}
                <flux:table.cell class="text-zinc-400 text-sm">
                    {{ $anggotas->firstItem() + $index }}
                </flux:table.cell>

                {{-- Foto: fixed 32x32, tidak bisa melar --}}
                <flux:table.cell>
                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        @if($anggota->foto)
                            <img src="{{ Storage::url($anggota->foto) }}"
                                 alt="{{ $anggota->nama_lengkap }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400">
                                {{ strtoupper(substr($anggota->nama_lengkap, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </flux:table.cell>

                {{-- Nomor Induk --}}
                <flux:table.cell>
                    <span class="font-mono text-xs text-zinc-600 dark:text-zinc-300">
                        {{ $anggota->nomor_induk }}
                    </span>
                </flux:table.cell>

                {{-- Nama --}}
                <flux:table.cell>
                    <div class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">
                        {{ $anggota->nama_lengkap }}
                    </div>
                    @if($anggota->tempat_lahir || $anggota->tanggal_lahir)
                        <div class="text-xs text-zinc-400 mt-0.5">
                            {{ $anggota->tempat_lahir }}
                            @if($anggota->tanggal_lahir)
                                {{ $anggota->tempat_lahir ? ', ' : '' }}{{ $anggota->tanggal_lahir->format('d/m/Y') }}
                            @endif
                        </div>
                    @endif
                </flux:table.cell>

                {{-- Jenis Kelamin --}}
                <flux:table.cell>
                    @if($anggota->jenis_kelamin === 'L')
                        <flux:badge color="blue" size="sm">Laki-laki</flux:badge>
                    @else
                        <flux:badge color="pink" size="sm">Perempuan</flux:badge>
                    @endif
                </flux:table.cell>

                {{-- Kelas / Status --}}
                <flux:table.cell>
                    @if($anggota->kelas)
                        <flux:badge color="zinc" size="sm">{{ $anggota->kelas }}</flux:badge>
                    @else
                        <flux:badge color="amber" size="sm">Guru / Staf</flux:badge>
                    @endif
                </flux:table.cell>

                {{-- Telepon --}}
                <flux:table.cell>
                    <span class="text-sm text-zinc-600 dark:text-zinc-300">
                        {{ $anggota->no_telepon ?? '—' }}
                    </span>
                </flux:table.cell>

                {{-- Aksi --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end gap-2">
                        <flux:button
                            variant="ghost" size="sm" icon="eye"
                            href="{{ route('anggota.show', $anggota->id) }}"
                            title="Lihat Detail"
                        />
                        <flux:button
                            variant="ghost" size="sm" icon="pencil-square"
                            href="{{ route('anggota.edit', $anggota->id) }}"
                            title="Edit"
                        />
                        <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                              onsubmit="return confirm('Hapus anggota \'{{ addslashes($anggota->nama_lengkap) }}\'? Tindakan ini tidak bisa dibatalkan.')">
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
                        <span class="text-4xl">👥</span>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada data anggota</p>
                        <p class="text-sm">
                            @if(request()->hasAny(['search','tipe','jenis_kelamin']))
                                Tidak ada anggota yang cocok dengan filter.
                                <a href="{{ route('anggota.index') }}" class="text-blue-500 hover:underline">Reset filter</a>
                            @else
                                Mulai tambahkan anggota perpustakaan.
                            @endif
                        </p>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    @if($anggotas->hasPages())
        <div>{{ $anggotas->links() }}</div>
    @endif

    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $anggotas->firstItem() ?? 0 }}–{{ $anggotas->lastItem() ?? 0 }}
        dari {{ $anggotas->total() }} anggota
    </p>

</div>
</x-layouts::app>