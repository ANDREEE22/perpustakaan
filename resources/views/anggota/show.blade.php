<x-layouts::app :title="__('Detail Anggota')">
<div class="max-w-3xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Avatar header: fixed 48x48 --}}
            <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                @if($anggota->foto)
                    <img src="{{ Storage::url($anggota->foto) }}"
                         alt="{{ $anggota->nama_lengkap }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-lg font-bold text-zinc-500">
                        {{ strtoupper(substr($anggota->nama_lengkap, 0, 1)) }}
                    </span>
                @endif
            </div>
            <div>
                <flux:heading size="xl" level="1">{{ $anggota->nama_lengkap }}</flux:heading>
                <flux:subheading>{{ $anggota->nomor_induk }} &bull; Detail Anggota</flux:subheading>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <flux:button variant="primary" icon="pencil-square" href="{{ route('anggota.edit', $anggota->id) }}">
                Edit Data
            </flux:button>
            <flux:button variant="ghost" icon="chevron-left" href="{{ route('anggota.index') }}">
                Kembali
            </flux:button>
        </div>
    </div>

    <flux:separator />

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Konten --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Lengkap --}}
        <div class="lg:col-span-2">
            <flux:card>
                <div class="space-y-6">

                    {{-- Foto + Identitas utama --}}
                    <div class="flex gap-5 items-start">
                        {{-- Foto: fixed 80x80 -- tidak bisa melar --}}
                        <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            @if($anggota->foto)
                                <img src="{{ Storage::url($anggota->foto) }}"
                                     alt="{{ $anggota->nama_lengkap }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-zinc-400">
                                    {{ strtoupper(substr($anggota->nama_lengkap, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-zinc-800 dark:text-zinc-100">
                                {{ $anggota->nama_lengkap }}
                            </h2>
                            <p class="font-mono text-sm text-zinc-500 mt-0.5">{{ $anggota->nomor_induk }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                @if($anggota->jenis_kelamin === 'L')
                                    <flux:badge color="blue">Laki-laki</flux:badge>
                                @else
                                    <flux:badge color="pink">Perempuan</flux:badge>
                                @endif

                                @if($anggota->kelas)
                                    <flux:badge color="zinc">Kelas {{ $anggota->kelas }}</flux:badge>
                                @else
                                    <flux:badge color="amber">Guru / Staf</flux:badge>
                                @endif
                            </div>
                        </div>
                    </div>

                    <flux:separator variant="subtle" />

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">Tempat, Tgl Lahir</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">
                                @if($anggota->tempat_lahir || $anggota->tanggal_lahir)
                                    {{ $anggota->tempat_lahir ?: '—' }},
                                    {{ $anggota->tanggal_lahir?->format('d/m/Y') ?? '—' }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div>
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">No Telepon</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1">
                                {{ $anggota->no_telepon ?: '—' }}
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <flux:label class="text-zinc-400 text-xs uppercase tracking-wider">Alamat</flux:label>
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 mt-1 leading-relaxed">
                                {{ $anggota->alamat ?: '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- Hapus --}}
            <div class="flex justify-start mt-4">
                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                      onsubmit="return confirm('Hapus anggota \'{{ addslashes($anggota->nama_lengkap) }}\'? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1.5 transition-all opacity-60 hover:opacity-100">
                        <flux:icon.trash variant="mini" />
                        Hapus anggota dari database
                    </button>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Info Keanggotaan --}}
        <div class="flex flex-col gap-5">
            <flux:card class="bg-zinc-50 dark:bg-white/[0.02] border-dashed">
                <div class="space-y-5">
                    <flux:heading size="sm">Info Keanggotaan</flux:heading>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <flux:icon.identification variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Nomor Induk</flux:label>
                                <p class="text-sm font-mono font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $anggota->nomor_induk }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.calendar variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Bergabung</flux:label>
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $anggota->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <flux:icon.clock variant="mini" class="mt-0.5 text-zinc-400" />
                            <div>
                                <flux:label class="text-[11px]">Terakhir Diperbarui</flux:label>
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $anggota->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </flux:card>

            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                <div class="flex gap-3">
                    <flux:icon.information-circle class="text-blue-400 shrink-0" />
                    <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                        Data anggota digunakan pada fitur peminjaman buku. Pastikan nomor induk dan nama lengkap sudah benar.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
</x-layouts::app>