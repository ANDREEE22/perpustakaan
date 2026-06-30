<x-layouts::app :title="__('Struktur Organisasi')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lib-teal:    #0f766e;
    --lib-emerald: #10b981;
    --lib-ink:     #1c1917;
    --lib-muted:   #78716c;
    --lib-border:  #e7e2da;
}
.lib-root { font-family: 'DM Sans', sans-serif; }
</style>

<div class="lib-root flex flex-col gap-6 w-full text-[var(--lib-ink)]">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1" style="font-family:'Lora', serif;">Struktur Organisasi</flux:heading>
            <flux:subheading>Kelola tingkatan pengurus dan staf perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button variant="ghost" icon="arrow-top-right-on-square" href="{{ route('home') }}" target="_blank">
                Lihat Website
            </flux:button>
            <flux:button variant="primary" icon="plus" href="{{ route('struktur.create') }}" style="background: var(--lib-teal); border:none; color: #fff;">
                Tambah Anggota
            </flux:button>
        </div>
    </div>

    <flux:separator />

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel Premium Flux --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-16">Foto</flux:table.column>
            <flux:table.column>Nama Lengkap</flux:table.column>
            <flux:table.column>Jabatan</flux:table.column>
            <flux:table.column>Level Urutan</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($strukturs as $item)
            <flux:table.row :key="$item->id">
                
                {{-- Foto --}}
                <flux:table.cell>
                    <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border flex items-center justify-center bg-zinc-50 dark:bg-zinc-800 text-zinc-400" style="border-color: var(--lib-border);">
                        @if($item->foto)
                            <img src="{{ Storage::url($item->foto) }}" class="w-full h-full object-cover">
                        @else
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        @endif
                    </div>
                </flux:table.cell>

                {{-- Nama --}}
                <flux:table.cell>
                    <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $item->nama }}</div>
                </flux:table.cell>

                {{-- Jabatan --}}
                <flux:table.cell>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $item->jabatan }}</span>
                </flux:table.cell>

                {{-- Level --}}
                <flux:table.cell>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                        Level {{ $item->level }}
                    </span>
                </flux:table.cell>

                {{-- Aksi Edit & Hapus --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end items-center gap-2">
                        <flux:button variant="ghost" size="sm" icon="pencil-square" href="{{ route('struktur.edit', $item->id) }}" title="Edit" />
                        
                        <form action="{{ route('struktur.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data personil ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer" title="Hapus">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-center py-12">
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 mb-2 border border-dashed border-zinc-200 dark:border-zinc-700">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada data struktur organisasi.</p>
                        <p class="text-sm">Silakan klik "Tambah Anggota" untuk memulai.</p>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

</div>
</x-layouts::app>