<x-layouts::app :title="__('Dashboard Perpustakaan')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lib-cream:   #fdf8f0;
    --lib-amber:   #d97706;
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

/* ── Banner ── */
.lib-banner {
    background: linear-gradient(135deg, #1c1208 0%, #3b1f0a 45%, #78350f 100%);
    border-radius: 22px; padding: 30px 34px;
    position: relative; overflow: hidden;
}
.lib-banner-rings { position: absolute; inset: 0; pointer-events: none; width: 100%; height: 100%; }
.lib-banner-rings circle { fill: none; stroke: rgba(255,255,255,0.04); }
.lib-banner h1 { font-family: 'Lora', serif; font-size: 1.55rem; font-weight: 700; color: #fef3c7; line-height: 1.25; }
.lib-banner p  { color: rgba(254,243,199,0.6); font-size: 0.86rem; margin-top: 5px; }
.lib-banner-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
    color: #fcd34d; font-size: 0.75rem; font-weight: 600;
    padding: 4px 12px; border-radius: 20px; margin-top: 12px;
}
.lib-book-icon {
    width: 60px; height: 60px; flex-shrink: 0;
    background: rgba(255,255,255,0.08); border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 16px; display: flex; align-items: center; justify-content: center;
}
.clock-time { font-family: 'Lora', serif; font-size: 1.8rem; font-weight: 700; color: #fef3c7; }
.clock-date { color: rgba(254,243,199,0.5); font-size: 0.75rem; margin-top: 2px; }

/* ── Stat Cards ── */
.stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
@media (min-width: 768px) { .stat-grid { grid-template-columns: repeat(4, 1fr); } }
.stat-card {
    background: var(--lib-surface); border: 1.5px solid var(--lib-border);
    border-radius: 18px; padding: 20px 18px;
    position: relative; overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
.stat-card-accent { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0; }
.stat-icon-wrap { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 14px; }
.stat-number { font-family: 'Lora', serif; font-size: 2rem; font-weight: 700; color: var(--lib-ink); line-height: 1; }
.stat-label  { font-size: 0.78rem; font-weight: 500; color: var(--lib-muted); margin-top: 4px; }
.stat-sub    { display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; margin-top: 8px; }

/* ── Section cards ── */
.lib-card { background: var(--lib-surface); border: 1.5px solid var(--lib-border); border-radius: 18px; overflow: hidden; }
.lib-card-header { padding: 16px 20px; border-bottom: 1.5px solid var(--lib-border); display: flex; align-items: center; justify-content: space-between; }
.lib-card-title  { font-family: 'Lora', serif; font-size: 0.95rem; font-weight: 600; color: var(--lib-ink); }
.lib-card-body   { padding: 16px 20px; }
.lib-link { font-size: 0.75rem; font-weight: 600; color: var(--lib-amber); text-decoration: none; }
.lib-link:hover  { text-decoration: underline; }

/* ── Peminjaman ── */
.borrow-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--lib-border); }
.borrow-item:last-child { border-bottom: none; }
.borrow-cover { width: 38px; height: 52px; border-radius: 6px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; overflow: hidden; }
.borrow-title { font-size: 0.84rem; font-weight: 600; color: var(--lib-ink); line-height: 1.3; }
.borrow-meta  { font-size: 0.72rem; color: var(--lib-muted); margin-top: 2px; }
.borrow-badge { font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 99px; white-space: nowrap; flex-shrink: 0; }
.badge-dipinjam     { background: #eff6ff; color: #1d4ed8; }
.badge-kembali      { background: #f0fdf4; color: #15803d; }
.badge-terlambat    { background: #fef2f2; color: #dc2626; }

/* ── Populer ── */
.popular-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--lib-border); }
.popular-item:last-child { border-bottom: none; }
.pop-rank { font-family: 'Lora', serif; font-size: 1.1rem; font-weight: 700; width: 28px; text-align: center; flex-shrink: 0; }
.pop-rank-1 { color: #f59e0b; }
.pop-rank-2 { color: #94a3b8; }
.pop-rank-3 { color: #b45309; }
.pop-rank-n { color: var(--lib-muted); font-size: 0.82rem; }
.pop-bar-wrap { flex: 1; height: 6px; background: var(--lib-border); border-radius: 99px; overflow: hidden; }
.pop-bar-fill { height: 100%; border-radius: 99px; background: var(--lib-amber); }
.pop-count { font-size: 0.72rem; font-weight: 700; color: var(--lib-muted); width: 28px; text-align: right; }

/* ── Sparkline ── */
.spark-wrap  { display: flex; align-items: flex-end; gap: 5px; height: 60px; }
.spark-bar   { flex: 1; border-radius: 4px 4px 0 0; background: linear-gradient(180deg, #d97706, #fbbf24); min-width: 10px; transition: opacity 0.2s; }
.spark-bar.today-bar { background: linear-gradient(180deg, #0f766e, #14b8a6); }
.spark-bar:hover { opacity: 0.7; }
.spark-labels { display: flex; gap: 5px; margin-top: 4px; }
.spark-label  { flex: 1; font-size: 0.6rem; text-align: center; color: var(--lib-muted); }

/* ── Denda ── */
.denda-item   { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--lib-border); }
.denda-item:last-child { border-bottom: none; }
.denda-avatar { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #fff; overflow: hidden; }
.denda-name   { font-size: 0.82rem; font-weight: 600; color: var(--lib-ink); }
.denda-kelas  { font-size: 0.7rem; color: var(--lib-muted); }
.denda-amt    { font-size: 0.82rem; font-weight: 700; color: #dc2626; white-space: nowrap; }

/* ── Buku Tamu ── */
.tamu-item  { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--lib-border); }
.tamu-item:last-child { border-bottom: none; }
.tamu-avatar { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; overflow: hidden; background: #fef3c7; color: #b45309; border: 1px solid var(--lib-border); }

/* ── Anim ── */
@keyframes libFadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
.a1 { animation: libFadeUp 0.4s 0.00s ease both; }
.a2 { animation: libFadeUp 0.4s 0.07s ease both; }
.a3 { animation: libFadeUp 0.4s 0.14s ease both; }
.a4 { animation: libFadeUp 0.4s 0.21s ease both; }
.a5 { animation: libFadeUp 0.4s 0.28s ease both; }
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
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" stroke="#fcd34d" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" stroke="#fcd34d" stroke-width="1.8"/>
                        <path d="M9 7h7M9 11h5" stroke="#fcd34d" stroke-width="1.5" stroke-linecap="round" opacity="0.6"/>
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
                <div style="color:rgba(254,243,199,0.35);font-size:0.7rem;margin-top:4px">TA 2024/2025</div>
            </div>
        </div>
    </div>

    {{-- ══ Stat Cards ══ --}}
    <div class="stat-grid a2">
        {{-- Total Koleksi --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#d97706"></div>
            <div class="stat-icon-wrap" style="background:#fffbeb">📚</div>
            <div class="stat-number">{{ number_format($totalBuku) }}</div>
            <div class="stat-label">Total Eksemplar</div>
            <div class="stat-sub" style="background:#fffbeb;color:#b45309">{{ $totalJudul }} judul buku</div>
        </div>
        {{-- Sedang Dipinjam --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#1d4ed8"></div>
            <div class="stat-icon-wrap" style="background:#eff6ff">🔖</div>
            <div class="stat-number">{{ $sedangDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-sub" style="background:{{ $totalTerlambat > 0 ? '#fef2f2' : '#eff6ff' }};color:{{ $totalTerlambat > 0 ? '#dc2626' : '#1d4ed8' }}">
                {{ $totalTerlambat > 0 ? '⚠️ ' . $totalTerlambat . ' terlambat' : 'Semua tepat waktu' }}
            </div>
        </div>
        {{-- Pengunjung Hari Ini --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#0f766e"></div>
            <div class="stat-icon-wrap" style="background:#f0fdfa">👥</div>
            <div class="stat-number">{{ $pengunjungHariIni }}</div>
            <div class="stat-label">Pengunjung Hari Ini</div>
            <div class="stat-sub" style="background:#f0fdfa;color:#0f766e">Total minggu ini: {{ $totalMingguIni }}</div>
        </div>
        {{-- Terlambat --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:{{ $totalTerlambat > 0 ? '#dc2626' : '#15803d' }}"></div>
            <div class="stat-icon-wrap" style="background:{{ $totalTerlambat > 0 ? '#fef2f2' : '#f0fdf4' }}">
                {{ $totalTerlambat > 0 ? '⚠️' : '✅' }}
            </div>
            <div class="stat-number">{{ $totalTerlambat }}</div>
            <div class="stat-label">Buku Terlambat</div>
            <div class="stat-sub" style="background:{{ $totalTerlambat > 0 ? '#fef2f2' : '#f0fdf4' }};color:{{ $totalTerlambat > 0 ? '#dc2626' : '#15803d' }}">
                {{ $totalTerlambat > 0 ? 'Perlu ditindak' : 'Tidak ada keterlambatan' }}
            </div>
        </div>
    </div>

    {{-- ══ Row: Peminjaman Terbaru + Menu Cepat ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 a3">

        {{-- Peminjaman Terbaru --}}
        <div class="lib-card md:col-span-2">
            <div class="lib-card-header">
                <span class="lib-card-title">🔖 Peminjaman Terbaru</span>
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
                    <div class="borrow-cover" style="background:#fef3c7">
                        @if($p->buku->sampul)
                            <img src="{{ asset('storage/' . $p->buku->sampul) }}" class="w-full h-full object-cover" alt="">
                        @else
                            📖
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
                <span class="lib-card-title">⚡ Menu Cepat</span>
            </div>
            <div class="lib-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    @php
                    $menus = [
                        ['icon'=>'📥','label'=>'Pinjam Buku','route'=>'pinjam.create'],
                        ['icon'=>'📤','label'=>'Kembalikan','route'=>'pinjam.index'],
                        ['icon'=>'📚','label'=>'Katalog','route'=>'katalog'],
                        ['icon'=>'➕','label'=>'Tambah Buku','route'=>'katalog.create'],
                        ['icon'=>'👤','label'=>'Anggota','route'=>'anggota.index'],
                        ['icon'=>'📋','label'=>'Buku Tamu','route'=>'kunjungan.index'],
                    ];
                    @endphp
                    @foreach($menus as $m)
                    <a href="{{ route($m['route']) }}"
                       style="display:flex;flex-direction:column;align-items:center;gap:7px;padding:14px 8px;border-radius:14px;border:1.5px solid var(--lib-border);background:var(--lib-cream);cursor:pointer;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 18px rgba(0,0,0,0.07)';this.style.borderColor='#d97706'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--lib-border)'">
                        <div style="font-size:1.4rem">{{ $m['icon'] }}</div>
                        <span style="font-size:0.73rem;font-weight:600;color:var(--lib-ink);text-align:center">{{ $m['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Row: Populer + Pengunjung ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 a4">

        {{-- Buku Terpopuler --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">🏆 Buku Terpopuler</span>
                <span style="font-size:0.72rem;color:var(--lib-muted)">berdasarkan peminjaman</span>
            </div>
            <div class="lib-card-body">
                @forelse($bukuPopuler as $i => $b)
                <div class="popular-item">
                    <div class="pop-rank {{ $i===0?'pop-rank-1':($i===1?'pop-rank-2':($i===2?'pop-rank-3':'pop-rank-n')) }}">
                        @if($i < 3) {{ ['①','②','③'][$i] }} @else {{ $i+1 }} @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:0.8rem;font-weight:600;color:var(--lib-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $b->judul }}</div>
                        <div class="pop-bar-wrap" style="margin-top:4px">
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
                <span class="lib-card-title">📈 Pengunjung 7 Hari Terakhir</span>
            </div>
            <div class="lib-card-body">
                <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:16px">
                    <span style="font-family:'Lora',serif;font-size:2rem;font-weight:700;color:var(--lib-ink)">{{ $totalMingguIni }}</span>
                    <span style="font-size:0.75rem;color:#0f766e;font-weight:600">kunjungan minggu ini</span>
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
                <div style="margin-top:16px;padding-top:14px;border-top:1.5px solid var(--lib-border)">
                    <div style="font-size:0.7rem;font-weight:700;color:var(--lib-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px">Pengunjung Hari Ini</div>
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
                            <div style="font-size:0.8rem;font-weight:600;color:var(--lib-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $kh->anggota->nama_lengkap }}</div>
                            <div style="font-size:0.68rem;color:var(--lib-muted)">{{ $kh->anggota->kelas ?? 'Guru' }} &bull; {{ $kh->keperluan }}</div>
                        </div>
                        <div style="font-size:0.68rem;font-family:monospace;color:var(--lib-muted)">{{ substr($kh->jam_masuk, 0, 5) }}</div>
                    </div>
                    @endforeach
                    @if($pengunjungHariIni > 5)
                    <a href="{{ route('kunjungan.index') }}" class="lib-link" style="display:block;text-align:center;margin-top:8px">+{{ $pengunjungHariIni - 5 }} lainnya →</a>
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
                <span class="lib-card-title">💸 Denda Aktif — Belum Dikembalikan</span>
                <a href="{{ route('pinjam.index', ['filter'=>'terlambat']) }}" class="lib-link">Kelola →</a>
            </div>
            <div class="lib-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 20px">
                    @foreach($dendaAktif as $d)
                    <div class="denda-item">
                        <div class="denda-avatar" style="background:{{ $d['clr'] }}">
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
                <div style="margin-top:12px;padding:10px 14px;background:#fef2f2;border-radius:10px;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:0.8rem;font-weight:600;color:#dc2626">Total Denda Tertunggak</span>
                    <span style="font-family:'Lora',serif;font-size:1rem;font-weight:700;color:#dc2626">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
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