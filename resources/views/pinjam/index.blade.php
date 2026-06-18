<x-layouts::app :title="__('Peminjaman Buku')">
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

/* ── Custom Stat Cards Premium Style ── */
.stat-grid-pjm { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
@media (min-width: 768px) { .stat-grid-pjm { grid-template-columns: repeat(4, 1fr); } }
.stat-card-pjm {
    background: #fff; border: 1.5px solid var(--lib-border);
    border-radius: 18px; padding: 20px 18px;
    position: relative; overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    text-decoration: none; display: block;
}
.dark .stat-card-pjm { background: #231f1c; border-color: #292524; }
.stat-card-pjm:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.06); }
.stat-card-accent-pjm { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0; }
.stat-icon-wrap-pjm { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 12px; }
.stat-number-pjm { font-family: 'Lora', serif; font-size: 1.8rem; font-weight: 700; color: var(--lib-ink); line-height: 1; }
.stat-label-pjm  { font-size: 0.75rem; font-weight: 600; color: var(--lib-muted); margin-top: 6px; }

/* ── Modal Animation ── */
@keyframes modalIn {
    from { opacity:0; transform: scale(0.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
</style>

<div class="lib-root flex flex-col gap-6 w-full text-[var(--lib-ink)]">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1" style="font-family:'Lora', serif;">Peminjaman Buku</flux:heading>
            <flux:subheading>Kelola transaksi pinjam & kembali perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('pinjam.create') }}" style="background: var(--lib-teal); border:none; color: #fff;">
            Pinjam Buku Baru
        </flux:button>
    </div>

    <flux:separator />

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info_denda'))
        <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-300">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-medium">{!! session('info_denda') !!}</span>
        </div>
    @endif

    {{-- Statistik Uniform Premium Minimalis --}}
    <div class="stat-grid-pjm">
        <a href="{{ route('pinjam.index') }}" class="stat-card-pjm">
            <div class="stat-icon-wrap-pjm bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="stat-number-pjm">{{ $stats['total'] }}</div>
            <div class="stat-label-pjm">Total Transaksi</div>
        </a>

        <a href="{{ route('pinjam.index', ['status'=>'dipinjam']) }}" class="stat-card-pjm">
            <div class="stat-icon-wrap-pjm bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="stat-number-pjm" style="color:#2563eb;">{{ $stats['aktif'] }}</div>
            <div class="stat-label-pjm">Sedang Dipinjam</div>
        </a>

        <a href="{{ route('pinjam.index', ['filter'=>'terlambat']) }}" class="stat-card-pjm">
            <div class="stat-icon-wrap-pjm bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="stat-number-pjm" style="color:#dc2626;">{{ $stats['terlambat'] }}</div>
            <div class="stat-label-pjm">Terlambat</div>
        </a>

        <a href="{{ route('pinjam.index', ['status'=>'kembali']) }}" class="stat-card-pjm">
            <div class="stat-icon-wrap-pjm bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-number-pjm" style="color:var(--lib-emerald);">{{ $stats['kembali'] }}</div>
            <div class="stat-label-pjm">Sudah Kembali</div>
        </a>
    </div>

    {{-- Filter & Search Panel --}}
    <form method="GET" action="{{ route('pinjam.index') }}" class="flex flex-col md:flex-row gap-4 items-end bg-white dark:bg-zinc-900 p-4 rounded-xl border" style="border-color: var(--lib-border);">
        <div class="flex-1 w-full">
            <flux:input name="search" icon="magnifying-glass"
                placeholder="Cari nama anggota atau judul buku..."
                value="{{ request('search') }}" clearable />
        </div>
        <div class="w-full md:w-48">
            <flux:select name="status" placeholder="Semua Status" clearable>
                <flux:select.option value="dipinjam" :selected="request('status') == 'dipinjam'">Dipinjam</flux:select.option>
                <flux:select.option value="kembali"  :selected="request('status') == 'kembali'">Sudah Kembali</flux:select.option>
            </flux:select>
        </div>
        <div class="w-full md:w-48">
            <flux:select name="filter" placeholder="Semua Kondisi" clearable>
                <flux:select.option value="terlambat" :selected="request('filter') == 'terlambat'">⚠️ Keterlambatan</flux:select.option>
            </flux:select>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto shrink-0">
            <flux:button type="submit" variant="primary" icon="magnifying-glass" style="background: var(--lib-teal); color:#fff; border:none;">Cari</flux:button>
            @if(request()->hasAny(['search','status','filter']))
                <flux:button href="{{ route('pinjam.index') }}" variant="ghost" icon="x-mark">Reset</flux:button>
            @endif
        </div>
    </form>

    {{-- Tabel Premium --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-10">No</flux:table.column>
            <flux:table.column>Anggota</flux:table.column>
            <flux:table.column>Buku</flux:table.column>
            <flux:table.column>Tgl Pinjam</flux:table.column>
            <flux:table.column>Harus Kembali</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Denda</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($peminjaman as $i => $p)
            @php
                $terlambat    = $p->isTerlambat();
                $hariTelat    = $p->hariTerlambat();
                $dendaPreview = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
            @endphp
            <flux:table.row :key="$p->id">

                {{-- No --}}
                <flux:table.cell class="text-zinc-400 text-sm">
                    {{ $peminjaman->firstItem() + $i }}
                </flux:table.cell>

                {{-- Anggota --}}
                <flux:table.cell>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 border flex items-center justify-center text-xs font-bold bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400" style="border-color: var(--lib-border);">
                            @if($p->anggota->foto)
                                <img src="{{ Storage::url($p->anggota->foto) }}" class="w-full h-full object-cover">
                            @else
                                <span style="color: var(--lib-teal);">{{ strtoupper(substr($p->anggota->nama_lengkap, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $p->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-zinc-400 font-mono">{{ $p->anggota->nomor_induk }}{{ $p->anggota->kelas ? ' · ' . $p->anggota->kelas : '' }}</div>
                        </div>
                    </div>
                </flux:table.cell>

                {{-- Buku --}}
                <flux:table.cell>
                    <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ Str::limit($p->buku->judul, 35) }}</div>
                    <div class="text-xs text-zinc-500">{{ $p->buku->pengarang }}</div>
                </flux:table.cell>

                {{-- Tgl Pinjam --}}
                <flux:table.cell>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400 font-mono">{{ $p->tgl_pinjam->format('d/m/Y') }}</span>
                </flux:table.cell>

                {{-- Harus Kembali --}}
                <flux:table.cell>
                    <span class="text-sm font-semibold font-mono {{ $terlambat ? 'text-red-600 dark:text-red-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                        {{ $p->tgl_harus_kembali->format('d/m/Y') }}
                        @if($terlambat && $p->status === 'dipinjam')
                            <span class="block text-[10px] font-bold text-red-500 uppercase tracking-wide">+{{ $hariTelat }} hari telat</span>
                        @endif
                    </span>
                </flux:table.cell>

                {{-- Status Badges --}}
                <flux:table.cell>
                    @if($p->status === 'kembali')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                            Selesai
                        </span>
                    @elseif($terlambat)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400">
                            Terlambat
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400">
                            Dipinjam
                        </span>
                    @endif
                </flux:table.cell>

                {{-- Denda --}}
                <flux:table.cell>
                    @if($dendaPreview > 0)
                        <span class="text-sm font-bold text-red-600 dark:text-red-400 font-mono">
                            Rp {{ number_format($dendaPreview, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-sm text-zinc-400">—</span>
                    @endif
                </flux:table.cell>

                {{-- Aksi --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end items-center gap-2">
                        <flux:button variant="ghost" size="sm" icon="eye"
                            href="{{ route('pinjam.show', $p->id) }}" title="Detail" />

                        @if($p->status === 'dipinjam')
                            <button
                                type="button"
                                onclick="konfirmasiKembali({{ $p->id }}, '{{ addslashes($p->anggota->nama_lengkap) }}', '{{ addslashes($p->buku->judul) }}', {{ $dendaPreview }}, {{ $hariTelat }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors cursor-pointer
                                       {{ $terlambat
                                           ? 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:border-red-700 dark:text-red-400'
                                           : 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-400' }}">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Kembalikan
                            </button>
                        @else
                            <span class="text-xs text-zinc-400 font-mono px-2">
                                {{ $p->tgl_realisasi_kembali?->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="8" class="text-center py-12">
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center text-zinc-400 mb-2 border border-dashed border-zinc-200 dark:border-zinc-700">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada data peminjaman</p>
                        <p class="text-sm">
                            @if(request()->hasAny(['search','status','filter']))
                                Tidak ada data yang cocok dengan filter.
                                <a href="{{ route('pinjam.index') }}" class="text-emerald-600 hover:underline">Reset filter</a>
                            @else
                                Mulai catat transaksi peminjaman buku pertama Anda.
                            @endif
                        </p>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    @if($peminjaman->hasPages())
        <div>{{ $peminjaman->links() }}</div>
    @endif
    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $peminjaman->firstItem() ?? 0 }}–{{ $peminjaman->lastItem() ?? 0 }}
        dari {{ $peminjaman->total() }} transaksi
    </p>

</div>

{{-- ── Modal Konfirmasi Kembalikan ── --}}
<div id="modal-kembali"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
     onclick="if(event.target===this)tutupModal()">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md shadow-2xl"
         style="animation: modalIn 0.25s ease">
        <div id="modal-icon" class="text-center text-4xl mb-3">📬</div>
        <flux:heading size="lg" class="text-center mb-2" id="modal-judul">Kembalikan Buku?</flux:heading>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center mb-5 leading-relaxed" id="modal-desc"></p>

        {{-- Info denda --}}
        <div id="modal-denda-box" class="hidden mb-5 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-red-500 font-bold text-sm">⚠️ Ada Keterlambatan!</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-red-600 dark:text-red-400">Keterlambatan: <strong id="modal-hari">0</strong> hari</span>
                <span class="text-base font-bold text-red-600 dark:text-red-400" id="modal-denda-nominal">Rp 0</span>
            </div>
            <p class="text-xs text-red-500 mt-1">Rp 500 × jumlah hari terlambat</p>
        </div>

        {{-- Info tepat waktu --}}
        <div id="modal-ok-box" class="hidden mb-5 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center gap-2">
                <span class="text-emerald-600 text-sm font-semibold">✅ Tepat Waktu — Tidak Ada Denda</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="tutupModal()"
                class="flex-1 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </button>
            <button id="btn-konfirmasi-kembali"
                class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors">
                Ya, Kembalikan
            </button>
        </div>
    </div>
</div>

{{-- ── Modal Hasil Pengembalian ── --}}
<div id="modal-hasil"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md shadow-2xl text-center"
         style="animation: modalIn 0.25s ease">
        <div class="text-5xl mb-3" id="hasil-icon">✅</div>
        <flux:heading size="lg" class="mb-2">Buku Berhasil Dikembalikan!</flux:heading>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4 leading-relaxed" id="hasil-desc"></p>
        <div id="hasil-denda-box" class="hidden mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <p class="text-sm text-red-600 dark:text-red-400 font-semibold mb-1">Denda yang harus dibayar:</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="hasil-denda-nominal">Rp 0</p>
        </div>
        <button onclick="location.reload()"
            class="w-full py-2.5 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold hover:opacity-90 transition-opacity">
            Tutup & Refresh
        </button>
    </div>
</div>

<script>
let currentPinjamId = null;
const btnKonfirmasi = document.getElementById('btn-konfirmasi-kembali');

function konfirmasiKembali(id, namaAnggota, judulBuku, denda, hariTelat) {
    currentPinjamId = id;

    document.getElementById('modal-desc').innerHTML =
        `<strong>${namaAnggota}</strong> mengembalikan buku<br><em>"${judulBuku}"</em>`;

    const dendaBox = document.getElementById('modal-denda-box');
    const okBox    = document.getElementById('modal-ok-box');

    if (denda > 0) {
        document.getElementById('modal-hari').textContent   = hariTelat;
        document.getElementById('modal-denda-nominal').textContent = 'Rp ' + denda.toLocaleString('id-ID');
        dendaBox.classList.remove('hidden');
        okBox.classList.add('hidden');
        document.getElementById('modal-icon').textContent  = '⚠️';
        document.getElementById('modal-judul').textContent = 'Ada Denda Keterlambatan';
        btnKonfirmasi.className = 'flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors';
    } else {
        dendaBox.classList.add('hidden');
        okBox.classList.remove('hidden');
        document.getElementById('modal-icon').textContent  = '📬';
        document.getElementById('modal-judul').textContent = 'Kembalikan Buku?';
        btnKonfirmasi.className = 'flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors';
    }

    // ensure button is enabled and shows original text when opening
    btnKonfirmasi.disabled = false;
    btnKonfirmasi.textContent = 'Ya, Kembalikan';

    document.getElementById('modal-kembali').classList.remove('hidden');
}

function tutupModal() {
    document.getElementById('modal-kembali').classList.add('hidden');
    currentPinjamId = null;
    btnKonfirmasi.disabled = false;
    btnKonfirmasi.textContent = 'Ya, Kembalikan';
}

btnKonfirmasi.addEventListener('click', async function () {
    if (!currentPinjamId) return;

    const btn = this;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    // Mengambil CSRF token. Pastikan layout utama blade memiliki tag meta csrf-token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    try {
        const res = await fetch(`/pinjam/${currentPinjamId}/kembalikan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({}),
        });

        let data;
        const contentType = res.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            data = await res.json();
        } else {
            const text = await res.text();
            data = { message: text };
        }

        if (!res.ok) {
            const msg = data.error || data.message || 'Terjadi kesalahan.';
            alert(msg);
            btn.disabled = false;
            btn.textContent = originalText;
            return;
        }

        // Success: clear modal state and show result
        currentPinjamId = null;
        btn.disabled = false;
        btn.textContent = originalText;

        document.getElementById('modal-kembali').classList.add('hidden');

        document.getElementById('hasil-desc').innerHTML =
            `<strong>${data.nama_anggota}</strong> telah mengembalikan<br><em>"${data.judul_buku}"</em><br>pada ${data.tgl_kembali}`;

        const hasilDendaBox = document.getElementById('hasil-denda-box');
        if (data.denda > 0) {
            document.getElementById('hasil-icon').textContent          = '⚠️';
            document.getElementById('hasil-denda-nominal').textContent = data.denda_format || ('Rp ' + (data.denda || 0).toLocaleString('id-ID'));
            hasilDendaBox.classList.remove('hidden');
        } else {
            document.getElementById('hasil-icon').textContent = '✅';
            hasilDendaBox.classList.add('hidden');
        }

        document.getElementById('modal-hasil').classList.remove('hidden');

    } catch (e) {
        console.error(e);
        alert('Gagal terhubung ke server. Periksa koneksi atau coba lagi.');
        btn.disabled = false;
        btn.textContent = originalText;
    }
});
</script>
</x-layouts::app>