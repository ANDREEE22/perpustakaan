<x-layouts::app :title="__('Manajemen Kategori')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lib-cream:   #fdf8f0;
    --lib-emerald: #10b981;
    --lib-teal:    #0f766e;
    --lib-ink:     #1c1917;
    --lib-muted:   #78716c;
    --lib-border:  #e7e2da;
    --lib-surface: #fff;
}
.dark {
    --lib-cream:   #1c1917;
    --lib-border:  #292524;
    --lib-surface: #231f1c;
    --lib-ink:     #fafaf9;
    --lib-muted:   #a8a29e;
}
.lib-root { font-family: 'DM Sans', sans-serif; }

/* ── Stat Cards (Disamakan Persis dengan Dashboard Baru) ── */
.lib-stat-card {
    background: var(--lib-surface); 
    border: 1.5px solid var(--lib-border);
    border-radius: 18px; 
    padding: 20px 18px;
    position: relative; 
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.lib-stat-card:hover { 
    transform: translateY(-3px); 
    box-shadow: 0 12px 28px rgba(0,0,0,0.06); 
}
.lib-stat-card-accent { 
    position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0; 
}
.lib-stat-icon-wrap { 
    width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 14px; 
}
.lib-stat-number { 
    font-family: 'Lora', serif; 
    font-size: 2rem; 
    font-weight: 700; 
    color: var(--lib-ink);
    line-height: 1; 
}
.lib-stat-label { 
    font-size: 0.78rem; 
    font-weight: 500; 
    color: var(--lib-muted); 
    margin-top: 4px; 
}
.lib-stat-sub { 
    display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; margin-top: 8px; 
}

/* Anim */
@keyframes libFadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
.a1 { animation: libFadeUp 0.4s 0.00s ease both; }
.a2 { animation: libFadeUp 0.4s 0.07s ease both; }
.a3 { animation: libFadeUp 0.4s 0.14s ease both; }
</style>

<div class="lib-root flex flex-col gap-6 w-full text-[var(--lib-ink)]">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4 a1">
        <div>
            <h1 class="text-2xl font-bold tracking-tight" style="font-family:'Lora', serif;">Manajemen Kategori</h1>
            <p class="text-sm mt-0.5" style="color: var(--lib-muted)">Kelola kategori buku perpustakaan SMPN 4 Jember</p>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('kategori.create') }}" style="background: var(--lib-teal); border:none;">
            Tambah Kategori
        </flux:button>
    </div>

    <flux:separator />

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 a1">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 a1">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ══ Stat Cards (Disamakan Desain & Icon Outline dengan Dashboard Baru) ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 a2">
        {{-- Total Kategori --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#eff6ff; color:#3b82f6">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="lib-stat-number" style="color: #3b82f6;">{{ $kategoris->total() }}</div>
            <div class="lib-stat-label">Total Kategori</div>
            <div class="lib-stat-sub" style="background:#eff6ff; color:#1d4ed8">Aktif digunakan</div>
        </div>

        {{-- Total Buku Terkategori --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#ecfdf5; color:#10b981">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="lib-stat-number" style="color: #10b981;">{{ $kategoris->sum('bukus_count') }}</div>
            <div class="lib-stat-label">Buku Terkategori</div>
            <div class="lib-stat-sub" style="background:#ecfdf5; color:#065f46">Eksemplar terarsip</div>
        </div>

        {{-- Kategori Kosong (Warna Biru Muted/Slate, Menghilangkan Amber/Orange) --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#f8fafc; color:#64748b">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5M9 5h6"/></svg>
            </div>
            <div class="lib-stat-number" style="color: #64748b;">{{ $kategoris->filter(fn($k) => $k->bukus_count == 0)->count() }}</div>
            <div class="lib-stat-label">Kategori Kosong</div>
            <div class="lib-stat-sub" style="background:#f1f5f9; color:#475569">Belum ada buku</div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="a3 flex flex-col gap-4">
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

                    {{-- Nama dengan Icon Outline Lebih Berkelas --}}
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">{{ $kategori->nama }}</span>
                        </div>
                    </flux:table.cell>

                    {{-- Jumlah Buku --}}
                    <flux:table.cell class="text-center">
                        @if($kategori->bukus_count > 0)
                            <a href="{{ route('katalog') }}?kategori={{ $kategori->id }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-semibold hover:bg-blue-100 transition-colors">
                                 {{ $kategori->bukus_count }} buku
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
                            <div class="w-12 h-12 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-2 border border-dashed border-zinc-200">
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada kategori</p>
                            <p class="text-sm">Mulai tambahkan kategori untuk mengelompokkan buku.</p>
                            <flux:button variant="primary" icon="plus" href="{{ route('kategori.create') }}" class="mt-2" style="background: var(--lib-teal); border:none;">
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

        {{-- Info Alert Premium (Clean Slate/Blue - Menghapus warna Oranye pudar) --}}
        <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/10 border border-slate-200 dark:border-slate-800">
            <span class="text-slate-500 text-lg shrink-0">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Kategori yang sudah memiliki buku <strong>tidak dapat dihapus</strong> demi keamanan integritas data. Pindahkan atau hapus buku terkait terlebih dahulu sebelum menghapus kategori.
            </p>
        </div>

        <p class="text-xs text-zinc-400 text-right">
            Menampilkan {{ $kategoris->firstItem() ?? 0 }}–{{ $kategoris->lastItem() ?? 0 }} dari {{ $kategoris->total() }} kategori
        </p>
    </div>

</div>
</x-layouts::app>