<x-layouts::app :title="__('Data Alumni')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <flux:heading size="xl" level="1">Data Alumni</flux:heading>
                <flux:subheading>Daftar siswa dan guru yang sudah lulus atau tidak aktif.</flux:subheading>
            </div>
            <flux:button href="{{ route('anggota.index') }}" variant="primary" icon="arrow-left" style="background: #0f766e; border: none; color: #fff;">
                Kembali ke Anggota Aktif
            </flux:button>
        </div>

        <flux:separator />

        <form method="GET" action="{{ route('alumni.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <flux:input
                    name="search"
                    icon="magnifying-glass"
                    placeholder="Cari nama atau nomor induk alumni..."
                    value="{{ request('search') }}"
                    clearable
                />
            </div>

            <div class="w-full md:w-44">
                <flux:select name="jenis_kelamin" placeholder="Semua JK">
                    <flux:select.option value="">Semua J. Kelamin</flux:select.option>
                    <flux:select.option value="L" :selected="request('jenis_kelamin') == 'L'">Laki-laki</flux:select.option>
                    <flux:select.option value="P" :selected="request('jenis_kelamin') == 'P'">Perempuan</flux:select.option>
                </flux:select>
            </div>

            <flux:button type="submit" variant="primary" icon="magnifying-glass" style="background: #0f766e; border: none; color: #fff;">Cari</flux:button>
            @if(request()->hasAny(['search', 'jenis_kelamin']))
                <flux:button href="{{ route('alumni.index') }}" variant="ghost" icon="x-mark">Reset</flux:button>
            @endif
        </form>

        <flux:table>
            <flux:table.columns>
                <flux:table.column class="w-10">No</flux:table.column>
                <flux:table.column>Nomor Induk</flux:table.column>
                <flux:table.column>Nama Lengkap</flux:table.column>
                <flux:table.column>J. Kelamin</flux:table.column>
                <flux:table.column>Kelas</flux:table.column>
                <flux:table.column>Tahun Keluar</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($alumni as $index => $anggota)
                    <flux:table.row :key="$anggota->id">
                        <flux:table.cell class="text-zinc-400 text-sm">
                            {{ $alumni->firstItem() + $index }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="font-mono text-xs text-zinc-600 dark:text-zinc-300">{{ $anggota->nomor_induk }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">{{ $anggota->nama_lengkap }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($anggota->jenis_kelamin === 'L')
                                <flux:badge color="blue" size="sm">Laki-laki</flux:badge>
                            @else
                                <flux:badge color="pink" size="sm">Perempuan</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($anggota->kelas)
                                <flux:badge color="zinc" size="sm">{{ $anggota->kelas }}</flux:badge>
                            @else
                                <flux:badge color="amber" size="sm">Guru / Staf</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-sm text-zinc-600 dark:text-zinc-300">{{ $anggota->tahun_keluar ?? '—' }}</span>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-2 text-zinc-400">
                                <span class="text-4xl">🎓</span>
                                <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada data alumni.</p>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        @if($alumni->hasPages())
            <div>{{ $alumni->links('vendor.pagination.custom') }}</div>
        @endif
    </div>
</x-layouts::app>
