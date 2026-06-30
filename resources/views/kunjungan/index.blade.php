<x-layouts::app :title="__('Buku Tamu Perpustakaan')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lib-cream:   #f0fdf4; /* Menggunakan basis hijau lembut sesuai dashboard */
    --lib-emerald: #10b981; /* Warna hijau emerald dashboard */
    --lib-teal:    #0f766e;
    --lib-ink:     #1c1917;
    --lib-muted:   #78716c;
    --lib-border:  #e7e2da;
    --lib-surface: #fff;
}
.dark {
    --lib-cream:   #14532d;
    --lib-border:  #292524;
    --lib-surface: #231f1c;
    --lib-ink:     #fafaf9;
    --lib-muted:   #a8a29e;
}
.lib-root { font-family: 'DM Sans', sans-serif; }

/* ── Custom Stat Cards (Disamakan Persis dengan Dashboard) ── */
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
    position: absolute; top: 0; left: 0; right: 0; height: 4px; border-radius: 18px 18px 0 0; 
}
.lib-stat-icon-wrap { 
    width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 14px; 
}
.lib-stat-number { 
    font-family: 'Lora', serif; 
    font-size: 2.2rem; 
    font-weight: 700; 
    color: var(--lib-ink);
    line-height: 1; 
}
.lib-stat-label { 
    font-size: 0.72rem; 
    font-weight: 700; 
    color: var(--lib-muted); 
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 8px; 
}
.lib-stat-sub { 
    display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; margin-top: 10px; 
}

/* ── Form & Card Components ── */
.lib-card { 
    background: var(--lib-surface); 
    border: 1.5px solid var(--lib-border); 
    border-radius: 18px; 
    overflow: hidden; 
}
.lib-card-header { 
    padding: 16px 20px; 
    border-bottom: 1.5px solid var(--lib-border); 
    display: flex; 
    align-items: center; 
    gap: 8px;
}
.lib-card-title { 
    font-family: 'Lora', serif; 
    font-size: 0.95rem; 
    font-weight: 600; 
    color: var(--lib-ink); 
}

/* ── Keperluan Buttons Grid ── */
.keperluan-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
.keperluan-btn {
    padding: 12px 10px;
    border-radius: 12px;
    border: 1.5px solid var(--lib-border);
    background: var(--lib-surface);
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--lib-ink);
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}
.keperluan-btn:hover {
    border-color: var(--lib-emerald);
    background: #f0fdf4;
}
.keperluan-btn.aktif {
    border-color: var(--lib-emerald);
    color: #065f46;
    background: #ecfdf5;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
}

/* Input Fields */
.lib-input {
    width: 100%;
    border: 1.5px solid var(--lib-border);
    background: var(--lib-surface);
    color: var(--lib-ink);
    border-radius: 12px;
    font-size: 0.85rem;
    padding: 12px 12px 12px 38px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.lib-input:focus {
    border-color: var(--lib-emerald);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Action Submit Button (Sama seperti Dashboard Baru) */
.btn-submit-lib {
    width: 100%;
    padding: 14px;
    border-radius: 14px;
    background: var(--lib-teal); 
    color: #fff;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}
.btn-submit-lib:hover:not(:disabled) {
    background: #065f55;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(15, 118, 110, 0.2);
}
.btn-submit-lib:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ── List Kunjungan Items ── */
.tamu-item { 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    padding: 14px 16px; 
    border-bottom: 1px solid var(--lib-border);
    background: var(--lib-surface);
    transition: background-color 0.2s;
}
.tamu-item:last-child { border-bottom: none; }
.tamu-item:hover {
    background-color: #fcfbf9;
}
.tamu-avatar { 
    width: 34px; 
    height: 34px; 
    border-radius: 50%; 
    flex-shrink: 0; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 0.78rem; 
    font-weight: 700; 
    background: #ecfdf5; 
    color: #065f46; 
    border: 1px solid var(--lib-border); 
    overflow: hidden; 
}

/* Anim */
@keyframes libFadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
.a1 { animation: libFadeUp 0.4s 0.00s ease both; }
.a2 { animation: libFadeUp 0.4s 0.08s ease both; }
</style>

<div class="lib-root flex flex-col gap-5 w-full text-[var(--lib-ink)]">

    {{-- Top Header Section --}}
    <div class="flex items-center justify-between flex-wrap gap-4 a1">
        <div>
            <h1 class="text-2xl font-bold tracking-tight" style="font-family:'Lora', serif;">Buku Tamu</h1>
            <p class="text-sm mt-0.5" style="color: var(--lib-muted)">Catat kunjungan siswa & guru ke perpustakaan SMPN 4 Jember</p>
        </div>
        {{-- Tanggal Hari Ini --}}
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl" style="background: #ecfdf5; border: 1.5px solid #bbf7d0;">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2" class="shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="text-xs font-bold" style="color: #065f46" id="tanggal-sekarang"></span>
        </div>
    </div>

    {{-- ══ Stat Cards (Disamakan Warna & Font Angka dengan Dashboard Baru) ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 a1">
        {{-- Pengunjung Hari Ini --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#f5f3ff; color:#8b5cf6">👥</div>
            <div class="lib-stat-number" id="stat-hari-ini">{{ $stats['hari_ini'] }}</div>
            <div class="lib-stat-label">Pengunjung Hari Ini</div>
            <div class="lib-stat-sub" style="background:#f1f3f4; color:#3c4043">Hari ini</div>
        </div>
        {{-- Bulan Ini --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#eff6ff; color:#3b82f6">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="lib-stat-number" style="color: #3b82f6;">{{ $stats['bulan_ini'] }}</div>
            <div class="lib-stat-label">Bulan Ini</div>
            <div class="lib-stat-sub" style="background:#eff6ff; color:#1d4ed8">Akumulasi</div>
        </div>
        {{-- Total Kunjungan --}}
        <div class="lib-stat-card">
            <div class="lib-stat-icon-wrap" style="background:#ecfdf5; color:#10b981">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="lib-stat-number" style="color: #10b981;">{{ $stats['total'] }}</div>
            <div class="lib-stat-label">Total Kunjungan</div>
            <div class="lib-stat-sub" style="background:#ecfdf5; color:#065f46">Semua Periode</div>
        </div>
    </div>

    {{-- Main Split View --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start a2">

        {{-- KIRI: FORM CHECK-IN --}}
        <div class="lg:col-span-2 flex flex-col gap-4">
            <div class="lib-card p-5 flex flex-col gap-4">
                
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #ecfdf5; color: var(--lib-emerald); border: 1px solid var(--lib-border)">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <span class="font-bold text-sm" style="font-family:'Lora', serif;">Check-in Pengunjung</span>
                </div>
                
                <hr style="border-color: var(--lib-border);" />

                {{-- Pencarian --}}
                <div>
                    <label class="text-[0.68rem] font-bold uppercase tracking-wider mb-2 block" style="color: var(--lib-muted)">
                        Cari NISN / Nama Anggota
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--lib-muted)" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                        <input
                            type="text"
                            id="kt-search"
                            placeholder="Ketik nomor induk atau nama..."
                            autocomplete="off"
                            class="lib-input"
                        >
                        {{-- Dropdown Auto-complete --}}
                        <div id="kt-dropdown"
                             class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl overflow-hidden max-h-56 overflow-y-auto">
                        </div>
                    </div>
                </div>

                {{-- Detail Terpilih --}}
                <div id="kt-selected" class="hidden p-3 rounded-xl border" style="background: #f8fafc; border-color: var(--lib-border);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border bg-white flex items-center justify-center text-xs font-bold" style="color: var(--lib-emerald); border-color: var(--lib-border)">
                            <img id="kt-foto" src="" alt="" class="hidden w-full h-full object-cover">
                            <span id="kt-inisial">A</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-sm truncate" id="kt-nama">—</div>
                            <div class="text-xs" id="kt-info" style="color: var(--lib-muted)">—</div>
                        </div>
                        <button type="button" onclick="clearPilihan()" class="hover:text-red-500 text-lg p-1 shrink-0 text-zinc-400">✕</button>
                    </div>
                    <input type="hidden" id="kt-anggota-id" value="">
                </div>

                {{-- Keperluan Buttons --}}
                <div>
                    <label class="text-[0.68rem] font-bold uppercase tracking-wider mb-2 block" style="color: var(--lib-muted)">
                        Keperluan Kunjungan
                    </label>
                    <div class="keperluan-grid">
                        @foreach(['Membaca', 'Pinjam Buku', 'Tugas', 'Belajar', 'Kembali Buku', 'Lainnya'] as $k)
                        <button type="button"
                                onclick="pilihKeperluan(this, '{{ $k }}')"
                                class="keperluan-btn">
                            {{ $k }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="kt-keperluan" value="Umum">
                </div>

                {{-- Button Submit --}}
                <button
                    id="btn-checkin"
                    type="button"
                    onclick="doCheckIn()"
                    class="btn-submit-lib"
                    disabled>
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Check-in Sekarang
                </button>

                <div id="kt-msg" class="hidden text-xs text-center font-semibold rounded-xl py-2.5 px-4"></div>

            </div>
        </div>

        {{-- KANAN: DAFTAR KUNJUNGAN HARI INI / HISTORI --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            {{-- Date Finder Header --}}
            <div class="lib-card p-4">
                <form method="GET" action="{{ route('kunjungan.index') }}" class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="text-[0.68rem] font-bold uppercase tracking-wider mb-1.5 block" style="color: var(--lib-muted)">Lihat Kunjungan Tanggal</label>
                        <input 
                            type="date" 
                            name="tanggal" 
                            value="{{ $tanggal->format('Y-m-d') }}"
                            class="w-full border border-zinc-200 dark:border-zinc-700 bg-transparent rounded-xl p-2.5 text-sm outline-none focus:border-[var(--lib-emerald)]"
                        />
                    </div>
                    <div>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-colors" style="background: var(--lib-teal); border:none; cursor:pointer;">
                            Cari
                        </button>
                    </div>
                    @if(request('tanggal') && request('tanggal') !== now()->format('Y-m-d'))
                        <div>
                            <a href="{{ route('kunjungan.index') }}" class="px-3 py-2.5 text-sm font-medium text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl block transition-colors">Hari Ini</a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Header List Meta --}}
            <div class="flex items-center justify-between border-b pb-2 mt-1" style="border-color: var(--lib-border);">
                <div class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5" style="color: var(--lib-muted);">
                    <span>📋 Daftar Kunjungan —</span>
                    <span style="color: var(--lib-emerald); font-family: 'Lora', serif;" class="normal-case text-sm font-bold">{{ $tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-600" id="jumlah-label">
                    {{ $kunjungan->count() }} orang
                </span>
            </div>

            {{-- Card List Container --}}
            <div class="lib-card flex flex-col overflow-hidden" id="kt-list">
                @forelse($kunjungan as $kv)
                    <div class="tamu-item" id="ktitem-{{ $kv->id }}">

                        {{-- Avatar --}}
                        <div class="tamu-avatar">
                            @if($kv->anggota->foto)
                                <img src="{{ asset('storage/' . $kv->anggota->foto) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ strtoupper(substr($kv->anggota->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>

                        {{-- Profil --}}
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold truncate" style="color: var(--lib-ink)">
                                {{ $kv->anggota->nama_lengkap }}
                            </div>
                            <div class="text-xs" style="color: var(--lib-muted)">
                                {{ $kv->anggota->nomor_induk }} @if($kv->anggota->kelas) &bull; Kelas {{ $kv->anggota->kelas }} @else &bull; Guru/Staf @endif
                            </div>
                        </div>

                        {{-- Badges Keperluan (Disamakan Warnanya) --}}
                        @php
                            $badgeStyle = match($kv->keperluan) {
                                'Membaca'      => 'background: #eff6ff; color: #1d4ed8;',
                                'Pinjam Buku'  => 'background: #fffbeb; color: #b45309;',
                                'Tugas'        => 'background: #faf5ff; color: #6b21a8;',
                                'Belajar'      => 'background: #f0fdf4; color: #166534;',
                                'Kembali Buku' => 'background: #f0fdfa; color: #0f766e;',
                                default        => 'background: #f1f3f4; color: #3c4043;',
                            };
                        @endphp
                        <span class="text-[0.68rem] font-bold px-2.5 py-1 rounded-full shrink-0" style="{{ $badgeStyle }}">
                            {{ $kv->keperluan ?? 'Umum' }}
                        </span>

                        {{-- Jam --}}
                        <div class="text-xs font-mono text-right shrink-0 w-12" style="color: var(--lib-muted)">
                            {{ substr($kv->jam_masuk, 0, 5) }}
                        </div>

                        {{-- Action Hapus Kunjungan --}}
                        <form action="{{ route('kunjungan.destroy', $kv->id) }}" method="POST"
                              onsubmit="return confirm('Hapus rekaman kunjungan tamu ini?')" class="shrink-0 m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-zinc-400 hover:text-red-500 hover:bg-red-50 transition-colors border-none bg-transparent cursor-pointer">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="text-center py-16" id="kt-empty">
                        <div class="text-4xl mb-2"></div>
                        <p class="font-bold text-sm" style="color: var(--lib-ink)">Belum ada kunjungan</p>
                        <p class="text-xs mt-1" style="color: var(--lib-muted)">
                            Silakan lakukan check-in pendaftaran tamu di panel sebelah kiri.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>

{{-- POPUP MODAL NOTIFIKASI SUKSES PREMIUM --}}
<div id="modal-sukses"
     class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 w-full max-w-xs text-center shadow-2xl"
         style="animation: libFadeUp 0.2s ease">
        <div class="text-4xl mb-2"></div>
        <h3 class="text-base font-bold mb-1" style="font-family:'Lora',serif;">Berhasil Check-In!</h3>
        <p class="text-xs mb-4 leading-relaxed" id="ms-desc" style="color: var(--lib-muted);"></p>
        <button onclick="document.getElementById('modal-sukses').classList.add('hidden')"
            class="w-full py-2.5 rounded-xl text-white text-xs font-semibold transition-colors bg-emerald-500 hover:bg-emerald-600 border-none cursor-pointer">
            Selesai
        </button>
    </div>
</div>

<script>
// Auto Date generator 
(function() {
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const now    = new Date();
    const el     = document.getElementById('tanggal-sekarang');
    if (el) el.textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
})();

// Data Source Injector Anggota
const DATA_ANGGOTA = {!! json_encode(
    \App\Models\Anggota::orderBy('nama_lengkap')
        ->get(['id', 'nomor_induk', 'nama_lengkap', 'kelas', 'foto'])
        ->toArray()
) !!};

let selectedAnggotaId   = null;
let selectedKeperluan   = 'Umum';

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const searchEl    = document.getElementById('kt-search');
const dropdownEl  = document.getElementById('kt-dropdown');

searchEl.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    if (q.length < 1) { dropdownEl.classList.add('hidden'); return; }

    const hasil = DATA_ANGGOTA.filter(a =>
        a.nama_lengkap.toLowerCase().includes(q) ||
        String(a.nomor_induk).toLowerCase().includes(q)
    ).slice(0, 6);

    if (!hasil.length) {
        dropdownEl.innerHTML = `<div class="px-4 py-3 text-center text-xs text-zinc-400">Tidak ditemukan.</div>`;
    } else {
        dropdownEl.innerHTML = hasil.map(a => {
            const avatar = a.foto
                ? `<img src="/storage/${escHtml(a.foto)}" class="w-full h-full object-cover">`
                : escHtml(a.nama_lengkap.charAt(0).toUpperCase());
            return `
            <button type="button"
                    data-id="${a.id}"
                    data-nama="${escHtml(a.nama_lengkap)}"
                    data-nisn="${escHtml(a.nomor_induk)}"
                    data-kelas="${escHtml(a.kelas ?? '')}"
                    data-foto="${escHtml(a.foto ?? '')}"
                    class="anggota-item w-full text-left px-4 py-2 hover:bg-zinc-50 flex items-center gap-3 border-b border-zinc-100 last:border-0 cursor-pointer">
                <div class="w-7 h-7 rounded-full overflow-hidden border bg-zinc-50 flex items-center justify-center text-[10px] font-bold shrink-0 text-emerald-600">
                    ${avatar}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-zinc-800 truncate">${escHtml(a.nama_lengkap)}</div>
                    <div class="text-[10px] text-zinc-400">${escHtml(a.nomor_induk)}${a.kelas ? ' · Kelas ' + escHtml(a.kelas) : ' · Guru/Staf'}</div>
                </div>
            </button>`;
        }).join('');

        dropdownEl.querySelectorAll('.anggota-item').forEach(btn => {
            btn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                pilihAnggota(this.dataset.id, this.dataset.nama, this.dataset.nisn, this.dataset.kelas, this.dataset.foto || null);
            });
        });
    }
    dropdownEl.classList.remove('hidden');
});

function pilihAnggota(id, nama, nisn, kelas, foto) {
    selectedAnggotaId = id;
    document.getElementById('kt-anggota-id').value = id;
    document.getElementById('kt-nama').textContent  = nama;
    document.getElementById('kt-info').textContent  = nisn + (kelas ? ' · Kelas ' + kelas : ' · Guru/Staf');
    document.getElementById('kt-inisial').textContent = nama.charAt(0).toUpperCase();

    const fotoEl    = document.getElementById('kt-foto');
    const inisialEl = document.getElementById('kt-inisial');
    if (foto) {
        fotoEl.src = '/storage/' + foto;
        fotoEl.classList.remove('hidden');
        inisialEl.classList.add('hidden');
    } else {
        fotoEl.classList.add('hidden');
        inisialEl.classList.remove('hidden');
    }

    document.getElementById('kt-selected').classList.remove('hidden');
    searchEl.value = '';
    dropdownEl.classList.add('hidden');
    document.getElementById('btn-checkin').disabled = false;
}

function clearPilihan() {
    selectedAnggotaId = null;
    document.getElementById('kt-anggota-id').value = '';
    document.getElementById('kt-selected').classList.add('hidden');
    document.getElementById('btn-checkin').disabled = true;
}

function pilihKeperluan(btn, nilai) {
    document.querySelectorAll('.keperluan-btn').forEach(b => b.classList.remove('aktif'));
    btn.classList.add('aktif');
    selectedKeperluan = nilai;
    document.getElementById('kt-keperluan').value = nilai;
}

async function doCheckIn() {
    if (!selectedAnggotaId) return;

    const btn = document.getElementById('btn-checkin');
    const msg = document.getElementById('kt-msg');
    btn.disabled = true;
    btn.innerHTML = 'Memproses...';

    try {
        const res  = await fetch('/api/check-in', {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     csrf(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                anggota_id: selectedAnggotaId,
                keperluan:  selectedKeperluan,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            msg.textContent = data.message ?? 'Gagal memproses.';
            msg.className   = 'text-xs text-center font-semibold rounded-xl py-2 px-3 bg-red-50 text-red-600';
            msg.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = 'Check-in Sekarang';
            return;
        }

        // Live Append tanpa reload halaman
        const badgeStyle = {
            'Membaca':      'background: #eff6ff; color: #1d4ed8;',
            'Pinjam Buku':  'background: #fffbeb; color: #b45309;',
            'Tugas':        'background: #faf5ff; color: #6b21a8;',
            'Belajar':      'background: #f0fdf4; color: #166534;',
            'Kembali Buku': 'background: #f0fdf4; color: #0f766e;',
        }[data.keperluan] ?? 'background: #f1f3f4; color: #3c4043;';

        const avatarHtml = data.foto
            ? `<img src="${data.foto}" class="w-full h-full object-cover">`
            : `${data.nama.charAt(0).toUpperCase()}`;

        const newRow = document.createElement('div');
        newRow.className = 'tamu-item';
        newRow.style.animation = 'libFadeUp 0.3s ease';
        newRow.innerHTML = `
            <div class="tamu-avatar">${avatarHtml}</div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-bold truncate">${data.nama}</div>
                <div class="text-xs text-zinc-400">${data.nomor_induk}${data.kelas ? ' &bull; Kelas ' + data.kelas : ' &bull; Guru/Staf'}</div>
            </div>
            <span class="text-[0.68rem] font-bold px-2.5 py-1 rounded-full shrink-0" style="${badgeStyle}">${data.keperluan}</span>
            <div class="text-xs font-mono text-right shrink-0 w-12 text-zinc-400">${data.jam_masuk}</div>
            <div class="w-7"></div>
        `;

        const emptyEl = document.getElementById('kt-empty');
        if (emptyEl) emptyEl.remove();

        const list = document.getElementById('kt-list');
        list.insertBefore(newRow, list.firstChild);

        // Mutasi hitungan di card stat atas
        document.getElementById('jumlah-label').textContent = data.jumlah_hari_ini + ' orang';
        document.getElementById('stat-hari-ini').textContent = data.jumlah_hari_ini;

        clearPilihan();
        document.querySelectorAll('.keperluan-btn').forEach(b => b.classList.remove('aktif'));

        document.getElementById('ms-desc').innerHTML = `Tamu <strong>${data.nama}</strong> berhasil disimpan pada jam <strong>${data.jam_masuk}</strong>.`;
        document.getElementById('modal-sukses').classList.remove('hidden');

    } catch (e) {
        msg.textContent = 'Koneksi error.';
        msg.className   = 'text-xs text-center bg-red-50 text-red-600';
        msg.classList.remove('hidden');
    }

    btn.disabled = false;
    btn.innerHTML = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Check-in Sekarang';
}

document.addEventListener('click', function(e) {
    if (!searchEl.contains(e.target) && !dropdownEl.contains(e.target)) {
        dropdownEl.classList.add('hidden');
    }
});

function escHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
</script>
</x-layouts::app>