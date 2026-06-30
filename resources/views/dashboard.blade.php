<x-layouts::app :title="__('Dashboard Perpustakaan')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap');

:root {
    --lib-cream:   #f8fafc; /* Slate 50 */
    --lib-amber:   #0f766e; /* Teal 700 (Primary Accent) */
    --lib-amber-light: #ccfbf1; /* Teal 100 */
    --lib-teal:    #0284c7; /* Sky 600 (Secondary Accent) */
    --lib-ink:     #0f172a; /* Slate 900 */
    --lib-muted:   #64748b; /* Slate 500 */
    --lib-border:  #e2e8f0; /* Slate 200 */
    --lib-surface: #ffffff;
    --lib-danger:  #e11d48; /* Rose 600 */
    --lib-success: #16a34a; /* Green 600 */
}
.dark {
    --lib-cream:   #0f172a;
    --lib-border:  #1e293b;
    --lib-surface: #1e293b;
    --lib-ink:     #f8fafc;
    --lib-muted:   #94a3b8;
}
.lib-root { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--lib-ink); }

/* ── Banner ── */
.lib-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%);
    border-radius: 16px; padding: 32px;
    position: relative; overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
}
.lib-banner-rings { position: absolute; inset: 0; pointer-events: none; width: 100%; height: 100%; }
.lib-banner-rings circle { fill: none; stroke: rgba(255,255,255,0.03); }
.lib-banner h1 { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #ffffff; line-height: 1.2; }
.lib-banner p  { color: #94a3b8; font-size: 0.875rem; margin-top: 6px; }
.lib-banner-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    color: #ccfbf1; font-size: 0.75rem; font-weight: 500;
    padding: 6px 14px; border-radius: 99px; margin-top: 14px;
}
.lib-book-icon {
    width: 56px; height: 56px; flex-shrink: 0;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
}
.clock-time { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 700; color: #ffffff; letter-spacing: -0.05em; }
.clock-date { color: #94a3b8; font-size: 0.75rem; margin-top: 2px; font-weight: 500; }

/* ── Stat Cards ── */
.stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (min-width: 768px) { .stat-grid { grid-template-columns: repeat(4, 1fr); } }
.stat-card {
    background: var(--lib-surface); border: 1px solid var(--lib-border);
    border-radius: 16px; padding: 20px;
    position: relative; overflow: hidden;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 20px -5px rgba(0,0,0,0.04); border-color: var(--lib-muted); }
.stat-icon-wrap { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.stat-number { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 700; color: var(--lib-ink); line-height: 1; letter-spacing: -0.02em; }
.stat-label  { font-size: 0.8rem; font-weight: 600; color: var(--lib-muted); margin-top: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
.stat-sub    { display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; font-weight: 500; padding: 4px 10px; border-radius: 99px; margin-top: 10px; }

/* ── Section cards ── */
.lib-card { background: var(--lib-surface); border: 1px solid var(--lib-border); border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
.lib-card-header { padding: 18px 22px; border-bottom: 1px solid var(--lib-border); display: flex; align-items: center; justify-content: space-between; }
.lib-card-title  { font-size: 0.95rem; font-weight: 700; color: var(--lib-ink); letter-spacing: -0.01em; }
.lib-card-body   { padding: 18px 22px; }
.lib-link { font-size: 0.75rem; font-weight: 600; color: var(--lib-amber); text-decoration: none; transition: color 0.2s; }
.lib-link:hover  { color: var(--lib-teal); text-decoration: none; }

/* ── Peminjaman ── */
.borrow-item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--lib-border); }
.borrow-item:last-child { border-bottom: none; }
.borrow-cover { width: 40px; height: 54px; border-radius: 6px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f1f5f9; border: 1px solid var(--lib-border); color: var(--lib-muted); }
.borrow-title { font-size: 0.875rem; font-weight: 600; color: var(--lib-ink); line-height: 1.3; }
.borrow-meta  { font-size: 0.75rem; color: var(--lib-muted); margin-top: 3px; }
.borrow-badge { font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 99px; white-space: nowrap; flex-shrink: 0; letter-spacing: 0.02em; }
.badge-dipinjam     { background: #e0f2fe; color: #0369a1; }
.badge-kembali      { background: #dcfce7; color: #15803d; }
.badge-terlambat    { background: #ffe4e6; color: #b91c1c; }

/* ── Populer ── */
.popular-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--lib-border); }
.popular-item:last-child { border-bottom: none; }
.pop-rank { font-size: 0.875rem; font-weight: 700; width: 24px; text-align: center; flex-shrink: 0; }
.pop-rank-1 { color: #0f766e; }
.pop-rank-2 { color: #0284c7; }
.pop-rank-3 { color: #64748b; }
.pop-rank-n { color: var(--lib-muted); font-size: 0.8rem; font-weight: 500; }
.pop-bar-wrap { flex: 1; height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
.pop-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #0f766e, #2dd4bf); }
.pop-count { font-size: 0.75rem; font-weight: 600; color: var(--lib-muted); width: 32px; text-align: right; }

/* ── Sparkline ── */
.spark-wrap  { display: flex; align-items: flex-end; gap: 6px; height: 60px; }
.spark-bar   { flex: 1; border-radius: 4px 4px 0 0; background: #cbd5e1; min-width: 10px; transition: all 0.2s; }
.spark-bar.today-bar { background: linear-gradient(180deg, #0f766e, #2dd4bf); }
.spark-bar:hover { filter: brightness(0.9); }
.spark-labels { display: flex; gap: 6px; margin-top: 6px; }
.spark-label  { flex: 1; font-size: 0.65rem; font-weight: 500; text-align: center; color: var(--lib-muted); }

/* ── Denda ── */
.denda-item   { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--lib-border); }
.denda-item:last-child { border-bottom: none; }
.denda-avatar { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: #fff; overflow: hidden; }
.denda-name   { font-size: 0.85rem; font-weight: 600; color: var(--lib-ink); }
.denda-kelas  { font-size: 0.72rem; color: var(--lib-muted); margin-top: 1px; }
.denda-amt    { font-size: 0.85rem; font-weight: 700; color: var(--lib-danger); white-space: nowrap; }

/* ── Buku Tamu ── */
.tamu-item  { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--lib-border); }
.tamu-item:last-child { border-bottom: none; }
.tamu-avatar { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; overflow: hidden; background: #e2e8f0; color: #334155; border: 1px solid var(--lib-border); }

/* ── Anim ── */
@keyframes libFadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.a1 { animation: libFadeUp 0.4s 0.00s ease both; }
.a2 { animation: libFadeUp 0.4s 0.05s ease both; }
.a3 { animation: libFadeUp 0.4s 0.10s ease both; }
.a4 { animation: libFadeUp 0.4s 0.15s ease both; }
.a5 { animation: libFadeUp 0.4s 0.20s ease both; }
</style>

<div class="lib-root flex h-full w-full flex-1 flex-col gap-4">

    {{-- ══ Banner ══ --}}
    <div class="lib-banner a1">
        <svg class="lib-banner-rings" viewBox="0 0 900 180" preserveAspectRatio="xMaxYMid slice">
            <circle cx="780" cy="90" r="130" stroke-width="1"/>
            <circle cx="780" cy="90" r="90"  stroke-width="0.8"/>
            <circle cx="780" cy="90" r="50"  stroke-width="0.6"/>
            <circle cx="640" cy="150" r="70" stroke-width="0.5"/>
        </svg>
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="lib-book-icon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" stroke="#2dd4bf" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" stroke="#2dd4bf" stroke-width="1.8"/>
                        <path d="M9 7h7M9 11h5" stroke="#2dd4bf" stroke-width="1.5" stroke-linecap="round" opacity="0.6"/>
                    </svg>
                </div>
                <div>
                    <h1>Perpustakaan SMPN 4 Jember</h1>
                    <p>Sistem Manajemen Perpustakaan — Pusat Literasi Sekolah</p>
                    <div class="lib-banner-chip">
                        <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        Gedung Perpustakaan — SMPN 4 Jember, Jawa Timur
                    </div>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <div class="clock-time" id="lib-time">--:--:--</div>
                <div class="clock-date" id="lib-date"></div>
                <div style="color:rgba(255,255,255,0.25);font-size:0.7rem;margin-top:4px;font-weight:600">TA 2026/2027</div>
            </div>
        </div>
    </div>

    {{-- ══ Stat Cards ══ --}}
    <div class="stat-grid a2">
        {{-- Total Koleksi --}}
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:#f0fdfa;color:#0f766e">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="stat-number">{{ number_format($totalBuku) }}</div>
            <div class="stat-label">Total Buku</div>
            <div class="stat-sub" style="background:#ccfbf1;color:#0f766e">{{ $totalJudul }} judul</div>
        </div>
        {{-- Sedang Dipinjam --}}
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:#f0f9ff;color:#0284c7">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="stat-number">{{ $sedangDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-sub" style="background:{{ $totalTerlambat > 0 ? '#ffe4e6' : '#e0f2fe' }};color:{{ $totalTerlambat > 0 ? '#b91c1c' : '#0369a1' }}">
                {{ $totalTerlambat > 0 ? $totalTerlambat . ' terlambat' : 'Semua tepat waktu' }}
            </div>
        </div>
        {{-- Pengunjung Hari Ini --}}
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:#f8fafc;color:#475569">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-number">{{ $pengunjungHariIni }}</div>
            <div class="stat-label">Pengunjung Hari Ini</div>
            <div class="stat-sub" style="background:#f1f5f9;color:#334155">Minggu ini: {{ $totalMingguIni }}</div>
        </div>
        {{-- Terlambat --}}
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:{{ $totalTerlambat > 0 ? '#ffe4e6' : '#dcfce7' }};color:{{ $totalTerlambat > 0 ? '#b91c1c' : '#16a34a' }}">
                @if($totalTerlambat > 0)
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>
            <div class="stat-number">{{ $totalTerlambat }}</div>
            <div class="stat-label">Buku Terlambat</div>
            <div class="stat-sub" style="background:{{ $totalTerlambat > 0 ? '#ffe4e6' : '#dcfce7' }};color:{{ $totalTerlambat > 0 ? '#b91c1c' : '#15803d' }}">
                {{ $totalTerlambat > 0 ? 'Perlu ditindak' : 'Aman bersih' }}
            </div>
        </div>
    </div>

    {{-- ══ Row: Peminjaman Terbaru + Menu Cepat ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 a3">

        {{-- Peminjaman Terbaru --}}
        <div class="lib-card md:col-span-2">
            <div class="lib-card-header">
                <span class="lib-card-title">Peminjaman Terbaru</span>
                <a href="{{ route('pinjam.index') }}" class="lib-link">Lihat Semua →</a>
            </div>
            <div class="lib-card-body">
                @forelse($peminjamanTerbaru as $p)
                @php
                    $terlambat = $p->status === 'dipinjam' && $p->tgl_harus_kembali->lt(now());
                    $badgeClass = $p->status === 'kembali' ? 'badge-kembali' : ($terlambat ? 'badge-terlambat' : 'badge-dipinjam');
                    $badgeLabel = $p->status === 'kembali' ? 'Dikembalikan' : ($terlambat ? 'Terlambat' : 'Dipinjam');
                @endphp
                <div class="borrow-item">
                    <div class="borrow-cover">
                        @if($p->buku->sampul)
                            <img src="{{ asset('storage/' . $p->buku->sampul) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="borrow-title" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->buku->judul }}</div>
                        <div class="borrow-meta">{{ $p->anggota->nama_lengkap }} &bull; {{ $p->anggota->kelas ?? 'Guru' }}</div>
                        <div class="borrow-meta">Pinjam: {{ $p->tgl_pinjam->format('d/m/Y') }} &bull; Kembali: {{ $p->tgl_harus_kembali->format('d/m/Y') }}</div>
                    </div>
                    <span class="borrow-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--lib-muted);font-size:0.85rem">
                    Belum ada transaksi peminjaman.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Menu Cepat --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">Menu Cepat</span>
            </div>
            <div class="lib-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    {{-- Pinjam Buku --}}
                    <a href="{{ route('pinjam.create') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #0284c7">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Pinjam Buku</span>
                    </a>

                    {{-- Kembalikan --}}
                    <a href="{{ route('pinjam.index') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #16a34a">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Kembalikan</span>
                    </a>

                    {{-- Katalog --}}
                    <a href="{{ route('katalog') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #0f766e">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Katalog</span>
                    </a>

                    {{-- Tambah Buku --}}
                    <a href="{{ route('katalog.create') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #6366f1">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Tambah Buku</span>
                    </a>

                    {{-- Anggota --}}
                    <a href="{{ route('anggota.index') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #475569">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Anggota</span>
                    </a>

                    {{-- Buku Tamu --}}
                    <a href="{{ route('kunjungan.index') }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 8px;border-radius:12px;border:1px solid var(--lib-border);background:#f8fafc;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 12px rgba(0,0,0,0.03)';this.style.borderColor='var(--lib-amber)';this.style.background='#ffffff'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)';this.style.background='#f8fafc'">
                        <div style="color: #ea580c">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                            </svg>
                        </div>
                        <span style="font-size:0.75rem;font-weight:600;color:#334155;text-align:center">Buku Tamu</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Row: Populer + Pengunjung ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 a4">

        {{-- Buku Terpopuler --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">Buku Terpopuler</span>
                <span style="font-size:0.75rem;color:var(--lib-muted);font-weight:500">Berdasarkan peminjaman</span>
            </div>
            <div class="lib-card-body">
                @forelse($bukuPopuler as $i => $b)
                <div class="popular-item">
                    <div class="pop-rank {{ $i===0?'pop-rank-1':($i===1?'pop-rank-2':($i===2?'pop-rank-3':'pop-rank-n')) }}">
                        {{ $i+1 }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:0.85rem;font-weight:600;color:var(--lib-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $b->judul }}</div>
                        <div class="pop-bar-wrap" style="margin-top:6px">
                            <div class="pop-bar-fill" style="width:{{ round($b->total_pinjam / $maxPinjam * 100) }}%"></div>
                        </div>
                    </div>
                    <div class="pop-count">{{ $b->total_pinjam }}x</div>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--lib-muted);font-size:0.85rem">
                    Belum ada data peminjaman.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pengunjung Mingguan --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">Pengunjung 7 Hari Terakhir</span>
            </div>
            <div class="lib-card-body">
                <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:18px">
                    <span style="font-size:1.75rem;font-weight:700;color:var(--lib-ink);letter-spacing:-0.03em">{{ $totalMingguIni }}</span>
                    <span style="font-size:0.75rem;color:#0f766e;font-weight:600">Total kunjungan minggu ini</span>
                </div>
                <div class="spark-wrap">
                    @foreach($kunjunganMingguan as $kv)
                    <div class="spark-bar {{ $kv['isToday'] ? 'today-bar' : '' }}"
                         style="height:{{ $maxKunjungan > 0 ? max(4, round($kv['jumlah'] / $maxKunjungan * 100)) : 4 }}%"
                         title="{{ $kv['label'] }}: {{ $kv['jumlah'] }} orang"></div>
                    @endforeach
                </div>
                <div class="spark-labels">
                    @foreach($kunjunganMingguan as $kv)
                    <div class="spark-label">{{ $kv['label'] }}</div>
                    @endforeach
                </div>

                {{-- Pengunjung hari ini --}}
                @if($kunjunganHariIni->isNotEmpty())
                <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--lib-border)">
                    <div style="font-size:0.7rem;font-weight:700;color:var(--lib-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px">Sesi Pengunjung Hari Ini</div>
                    @foreach($kunjunganHariIni as $kh)
                    <div class="tamu-item">
                        <div class="tamu-avatar">
                            @if($kh->anggota->foto)
                                <img src="{{ asset('storage/' . $kh->anggota->foto) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ strtoupper(substr($kh->anggota->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:0.85rem;font-weight:600;color:var(--lib-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $kh->anggota->nama_lengkap }}</div>
                            <div style="font-size:0.75rem;color:var(--lib-muted)">{{ $kh->anggota->kelas ?? 'Guru' }} &bull; {{ $kh->keperluan }}</div>
                        </div>
                        <div style="font-size:0.72rem;font-family:monospace;color:var(--lib-muted);font-weight:500">{{ substr($kh->jam_masuk, 0, 5) }}</div>
                    </div>
                    @endforeach
                    @if($pengunjungHariIni > 5)
                    <a href="{{ route('kunjungan.index') }}" class="lib-link" style="display:block;text-align:center;margin-top:10px">+{{ $pengunjungHariIni - 5 }} lainnya →</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ Row: Denda Aktif ══ --}}
    @if($dendaAktif->isNotEmpty())
    <div class="a5">
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">Keterlambatan Pengembalian & Denda</span>
                <a href="{{ route('pinjam.index', ['filter'=>'terlambat']) }}" class="lib-link">Kelola Transaksi →</a>
            </div>
            <div class="lib-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 24px">
                    @foreach($dendaAktif as $d)
                    <div class="denda-item">
                        <div class="denda-avatar" style="background:#ffe4e6;color:#b91c1c">
                            @if($d['foto'])
                                <img src="{{ asset('storage/' . $d['foto']) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ $d['inisial'] }}
                            @endif
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="denda-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $d['nama'] }}</div>
                            <div class="denda-kelas">{{ $d['kelas'] }} &bull; {{ Str::limit($d['buku'], 20) }} &bull; {{ $d['hari'] }} hari</div>
                        </div>
                        <div class="denda-amt">{{ $d['denda'] }}</div>
                    </div>
                    @endforeach
                </div>
                @if($totalDenda > 0)
                <div style="margin-top:16px;padding:12px 16px;background:#fff1f2;border:1px solid #ffe4e6;border-radius:10px;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:0.8rem;font-weight:700;color:#b91c1c;text-transform:uppercase;letter-spacing:0.02em">Total Denda Tertunggak</span>
                    <span style="font-size:1.1rem;font-weight:800;color:#b91c1c">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

<script>
(function() {
    function tick() {
        const now    = new Date();
        const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const t = document.getElementById('lib-time');
        const d = document.getElementById('lib-date');
        if (t) t.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
        if (d) d.textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
</x-layouts::app>