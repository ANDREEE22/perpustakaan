<x-layouts::app :title="__('Dashboard Perpustakaan')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lib-cream:   #fdf8f0;
    --lib-brown:   #7c4f2a;
    --lib-amber:   #d97706;
    --lib-teal:    #0f766e;
    --lib-rose:    #be185d;
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
    border-radius: 22px;
    padding: 30px 34px;
    position: relative;
    overflow: hidden;
}
.lib-banner-rings {
    position: absolute; inset: 0; pointer-events: none; width: 100%; height: 100%;
}
.lib-banner-rings circle { fill: none; stroke: rgba(255,255,255,0.04); }
.lib-banner h1 {
    font-family: 'Lora', serif;
    font-size: 1.55rem; font-weight: 700;
    color: #fef3c7; line-height: 1.25;
}
.lib-banner p { color: rgba(254,243,199,0.6); font-size: 0.86rem; margin-top: 5px; }
.lib-banner-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.14);
    color: #fcd34d; font-size: 0.75rem; font-weight: 600;
    padding: 4px 12px; border-radius: 20px; margin-top: 12px;
}
.lib-book-icon {
    width: 60px; height: 60px; flex-shrink: 0;
    background: rgba(255,255,255,0.08);
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
}
.clock-time {
    font-family: 'Lora', serif;
    font-size: 1.8rem; font-weight: 700;
    color: #fef3c7; letter-spacing: 0.02em;
}
.clock-date { color: rgba(254,243,199,0.5); font-size: 0.75rem; margin-top: 2px; }

/* ── Stat Cards ── */
.stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
@media (min-width: 768px) { .stat-grid { grid-template-columns: repeat(4, 1fr); } }

.stat-card {
    background: var(--lib-surface);
    border: 1.5px solid var(--lib-border);
    border-radius: 18px;
    padding: 20px 18px;
    position: relative; overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
.stat-card-accent {
    position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0;
}
.stat-icon-wrap {
    width: 42px; height: 42px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; margin-bottom: 14px;
}
.stat-number {
    font-family: 'Lora', serif;
    font-size: 2rem; font-weight: 700; color: var(--lib-ink); line-height: 1;
}
.stat-label { font-size: 0.78rem; font-weight: 500; color: var(--lib-muted); margin-top: 4px; }
.stat-sub {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 0.7rem; font-weight: 600;
    padding: 2px 8px; border-radius: 99px; margin-top: 8px;
}

/* ── Generic section card ── */
.lib-card {
    background: var(--lib-surface);
    border: 1.5px solid var(--lib-border);
    border-radius: 18px;
    overflow: hidden;
}
.lib-card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--lib-border);
    display: flex; align-items: center; justify-content: space-between;
}
.lib-card-title {
    font-family: 'Lora', serif;
    font-size: 0.95rem; font-weight: 600;
    color: var(--lib-ink);
}
.lib-card-body { padding: 16px 20px; }
.lib-link { font-size: 0.75rem; font-weight: 600; color: var(--lib-amber); text-decoration: none; }
.lib-link:hover { text-decoration: underline; }

/* ── Peminjaman terbaru ── */
.borrow-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--lib-border);
}
.borrow-item:last-child { border-bottom: none; }
.borrow-cover {
    width: 38px; height: 52px; border-radius: 6px;
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.borrow-title { font-size: 0.84rem; font-weight: 600; color: var(--lib-ink); line-height: 1.3; }
.borrow-meta { font-size: 0.72rem; color: var(--lib-muted); margin-top: 2px; }
.borrow-badge {
    font-size: 0.68rem; font-weight: 700; padding: 3px 9px;
    border-radius: 99px; white-space: nowrap; flex-shrink: 0;
}
.badge-dipinjam      { background: #eff6ff; color: #1d4ed8; }
.badge-dikembalikan  { background: #f0fdf4; color: #15803d; }
.badge-terlambat     { background: #fef2f2; color: #dc2626; }

/* ── Koleksi Populer ── */
.popular-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--lib-border);
}
.popular-item:last-child { border-bottom: none; }
.pop-rank {
    font-family: 'Lora', serif;
    font-size: 1.1rem; font-weight: 700;
    width: 28px; text-align: center; flex-shrink: 0;
}
.pop-rank-1 { color: #f59e0b; }
.pop-rank-2 { color: #94a3b8; }
.pop-rank-3 { color: #b45309; }
.pop-rank-n { color: var(--lib-muted); font-size: 0.82rem; }
.pop-bar-wrap { flex: 1; height: 6px; background: var(--lib-border); border-radius: 99px; overflow: hidden; }
.pop-bar-fill { height: 100%; border-radius: 99px; background: var(--lib-amber); }
.pop-count { font-size: 0.72rem; font-weight: 700; color: var(--lib-muted); width: 28px; text-align: right; }

/* ── Sparkline chart ── */
.spark-wrap { display: flex; align-items: flex-end; gap: 5px; height: 60px; }
.spark-bar {
    flex: 1; border-radius: 4px 4px 0 0;
    background: linear-gradient(180deg, #d97706, #fbbf24);
    min-width: 10px; cursor: pointer;
    transition: opacity 0.2s;
}
.spark-bar.today-bar { background: linear-gradient(180deg, #0f766e, #14b8a6); }
.spark-bar:hover { opacity: 0.7; }
.spark-labels { display: flex; gap: 5px; margin-top: 4px; }
.spark-label { flex: 1; font-size: 0.6rem; text-align: center; color: var(--lib-muted); }

/* ── Rak ── */
.rak-item { display: flex; gap: 10px; align-items: center; padding: 7px 0; border-bottom: 1px solid var(--lib-border); }
.rak-item:last-child { border-bottom: none; }
.rak-label { font-size: 0.82rem; font-weight: 600; color: var(--lib-ink); flex: 1; }
.rak-sub { font-size: 0.7rem; color: var(--lib-muted); }
.rak-bar-wrap { width: 80px; height: 7px; background: var(--lib-border); border-radius: 99px; overflow: hidden; }
.rak-bar-fill { height: 100%; border-radius: 99px; transition: width 0.6s ease; }

/* ── Denda ── */
.denda-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--lib-border); }
.denda-item:last-child { border-bottom: none; }
.denda-avatar {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700; color: #fff;
}
.denda-name { font-size: 0.82rem; font-weight: 600; color: var(--lib-ink); }
.denda-kelas { font-size: 0.7rem; color: var(--lib-muted); }
.denda-amt { font-size: 0.82rem; font-weight: 700; color: #dc2626; white-space: nowrap; }

/* ── Agenda ── */
.agenda-item { display: flex; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--lib-border); }
.agenda-item:last-child { border-bottom: none; }
.agenda-date-box {
    width: 42px; flex-shrink: 0; text-align: center;
    background: var(--lib-cream); border-radius: 10px; padding: 6px 4px;
    border: 1.5px solid var(--lib-border);
}
.agenda-date-num { font-family: 'Lora', serif; font-size: 1.2rem; font-weight: 700; color: var(--lib-ink); line-height: 1; }
.agenda-date-mon { font-size: 0.6rem; font-weight: 600; color: var(--lib-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.agenda-title { font-size: 0.84rem; font-weight: 600; color: var(--lib-ink); }
.agenda-desc { font-size: 0.71rem; color: var(--lib-muted); margin-top: 2px; }

/* ── Quick Actions ── */
.qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.qa-btn {
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    padding: 14px 8px; border-radius: 14px;
    border: 1.5px solid var(--lib-border);
    background: var(--lib-cream);
    cursor: pointer; text-decoration: none;
    transition: all 0.2s;
}
.qa-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,0.07); border-color: var(--lib-amber); }
.qa-icon { font-size: 1.4rem; }
.qa-lbl { font-size: 0.73rem; font-weight: 600; color: var(--lib-ink); text-align: center; }

/* animations */
@keyframes libFadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
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
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#d97706"></div>
            <div class="stat-icon-wrap" style="background:#fffbeb">📚</div>
            <div class="stat-number">3.247</div>
            <div class="stat-label">Total Koleksi Buku</div>
            <div class="stat-sub" style="background:#fffbeb;color:#b45309">↑ +24 judul baru</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#1d4ed8"></div>
            <div class="stat-icon-wrap" style="background:#eff6ff">🔖</div>
            <div class="stat-number">148</div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-sub" style="background:#eff6ff;color:#1d4ed8">dari 842 siswa</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#0f766e"></div>
            <div class="stat-icon-wrap" style="background:#f0fdfa">👥</div>
            <div class="stat-number">87</div>
            <div class="stat-label">Pengunjung Hari Ini</div>
            <div class="stat-sub" style="background:#f0fdfa;color:#0f766e">↑ +11 dari kemarin</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#dc2626"></div>
            <div class="stat-icon-wrap" style="background:#fef2f2">⚠️</div>
            <div class="stat-number">19</div>
            <div class="stat-label">Terlambat Kembali</div>
            <div class="stat-sub" style="background:#fef2f2;color:#dc2626">belum dikembalikan</div>
        </div>
    </div>

    {{-- ══ Row: Peminjaman + Quick Actions ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 a3">

        <div class="lib-card md:col-span-2">
            <div class="lib-card-header">
                <span class="lib-card-title">🔖 Peminjaman Terbaru</span>
                <a href="#" class="lib-link">Lihat Semua →</a>
            </div>
            <div class="lib-card-body">
                @php
                $borrows = [
                    ['emoji'=>'📖','bg'=>'#fef3c7','judul'=>'Laskar Pelangi','penulis'=>'Andrea Hirata','peminjam'=>'Dina Aulia — 8B','tgl'=>'Hari ini, 08.12','status'=>'dipinjam'],
                    ['emoji'=>'🔬','bg'=>'#ecfdf5','judul'=>'IPA Terpadu Kelas 9','penulis'=>'Kemendikbud','peminjam'=>'Rafi Ardian — 9A','tgl'=>'Hari ini, 09.45','status'=>'dipinjam'],
                    ['emoji'=>'📐','bg'=>'#eff6ff','judul'=>'Matematika SMP Kelas 8','penulis'=>'Sukino, dkk','peminjam'=>'Siti Rahma — 8C','tgl'=>'Kemarin, 13.20','status'=>'dikembalikan'],
                    ['emoji'=>'🗺️','bg'=>'#f5f3ff','judul'=>'Atlas Dunia Lengkap','penulis'=>'Gramedia','peminjam'=>'Bima Saputra — 7D','tgl'=>'2 hari lalu','status'=>'terlambat'],
                    ['emoji'=>'📜','bg'=>'#fff7ed','judul'=>'Sejarah Indonesia Modern','penulis'=>'Sartono Kartodirdjo','peminjam'=>'Nisa Fitriani — 9C','tgl'=>'2 hari lalu','status'=>'dipinjam'],
                ];
                @endphp
                @foreach($borrows as $b)
                <div class="borrow-item">
                    <div class="borrow-cover" style="background:{{ $b['bg'] }}">{{ $b['emoji'] }}</div>
                    <div style="flex:1;min-width:0">
                        <div class="borrow-title" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $b['judul'] }}</div>
                        <div class="borrow-meta">{{ $b['penulis'] }} &bull; {{ $b['peminjam'] }}</div>
                        <div class="borrow-meta">{{ $b['tgl'] }}</div>
                    </div>
                    <span class="borrow-badge badge-{{ $b['status'] }}">{{ ucfirst($b['status']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">⚡ Menu Cepat</span>
            </div>
            <div class="lib-card-body">
                <div class="qa-grid">
                    <a href="#" class="qa-btn"><div class="qa-icon">📥</div><span class="qa-lbl">Pinjam Buku</span></a>
                    <a href="#" class="qa-btn"><div class="qa-icon">📤</div><span class="qa-lbl">Kembalikan</span></a>
                    <a href="#" class="qa-btn"><div class="qa-icon">🔍</div><span class="qa-lbl">Cari Koleksi</span></a>
                    <a href="#" class="qa-btn"><div class="qa-icon">➕</div><span class="qa-lbl">Tambah Buku</span></a>
                    <a href="#" class="qa-btn"><div class="qa-icon">👤</div><span class="qa-lbl">Data Anggota</span></a>
                    <a href="#" class="qa-btn"><div class="qa-icon">📊</div><span class="qa-lbl">Laporan</span></a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Row: Populer + Pengunjung + Rak ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 a4">

        {{-- Buku Terpopuler --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">🏆 Buku Terpopuler</span>
                <span style="font-size:0.72rem;color:var(--lib-muted)">bulan ini</span>
            </div>
            <div class="lib-card-body">
                @php
                $popular = [
                    ['judul'=>'Laskar Pelangi','pinjam'=>48,'max'=>48],
                    ['judul'=>'Harry Potter #1','pinjam'=>41,'max'=>48],
                    ['judul'=>'Bumi Manusia','pinjam'=>35,'max'=>48],
                    ['judul'=>'IPA Terpadu Kls 9','pinjam'=>31,'max'=>48],
                    ['judul'=>'Sang Pemimpi','pinjam'=>27,'max'=>48],
                    ['judul'=>'Matematika Kls 8','pinjam'=>22,'max'=>48],
                ];
                @endphp
                @foreach($popular as $i => $p)
                <div class="popular-item">
                    <div class="pop-rank {{ $i===0?'pop-rank-1':($i===1?'pop-rank-2':($i===2?'pop-rank-3':'pop-rank-n')) }}">
                        @if($i < 3) {{ ['①','②','③'][$i] }} @else {{ $i+1 }} @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:0.8rem;font-weight:600;color:var(--lib-ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p['judul'] }}</div>
                        <div class="pop-bar-wrap" style="margin-top:4px">
                            <div class="pop-bar-fill" style="width:{{ round($p['pinjam']/$p['max']*100) }}%"></div>
                        </div>
                    </div>
                    <div class="pop-count">{{ $p['pinjam'] }}x</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pengunjung Mingguan --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">📈 Pengunjung Minggu Ini</span>
            </div>
            <div class="lib-card-body">
                <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:16px">
                    <span style="font-family:'Lora',serif;font-size:2rem;font-weight:700;color:var(--lib-ink)">436</span>
                    <span style="font-size:0.75rem;color:#0f766e;font-weight:600">↑ 8.4% vs minggu lalu</span>
                </div>
                @php
                $days   = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                $visits = [72, 68, 91, 85, 87, 33, 0];
                $maxV   = max($visits) ?: 1;
                @endphp
                <div class="spark-wrap">
                    @foreach($visits as $idx => $v)
                    <div class="spark-bar {{ $idx===4?'today-bar':'' }}"
                         style="height:{{ max(4, round($v/$maxV*100)) }}%"
                         title="{{ $days[$idx] }}: {{ $v }} orang"></div>
                    @endforeach
                </div>
                <div class="spark-labels">
                    @foreach($days as $d)<div class="spark-label">{{ $d }}</div>@endforeach
                </div>
                <div style="margin-top:16px;padding-top:14px;border-top:1.5px solid var(--lib-border)">
                    <div style="font-size:0.7rem;font-weight:700;color:var(--lib-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px">Jam Ramai</div>
                    @php
                    $jams = [['07–09','60','#d97706'],['09–11','100','#0f766e'],['11–13','70','#d97706'],['13–15','30','#94a3b8']];
                    @endphp
                    @foreach($jams as $j)
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
                        <span style="font-size:0.7rem;color:var(--lib-muted);width:40px;flex-shrink:0">{{ $j[0] }}</span>
                        <div style="flex:1;height:6px;background:var(--lib-border);border-radius:99px;overflow:hidden">
                            <div style="height:100%;border-radius:99px;background:{{ $j[2] }};width:{{ $j[1] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kapasitas Rak --}}
        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">🗂️ Kapasitas Rak</span>
            </div>
            <div class="lib-card-body">
                @php
                $rak = [
                    ['nama'=>'Fiksi & Sastra','sub'=>'Rak A1–A4','pct'=>82,'warna'=>'#d97706'],
                    ['nama'=>'Ilmu Pengetahuan','sub'=>'Rak B1–B6','pct'=>67,'warna'=>'#0f766e'],
                    ['nama'=>'Referensi / Kamus','sub'=>'Rak C1–C2','pct'=>91,'warna'=>'#dc2626'],
                    ['nama'=>'Komik & Majalah','sub'=>'Rak D1–D3','pct'=>55,'warna'=>'#7c3aed'],
                    ['nama'=>'Pelajaran SMP','sub'=>'Rak E1–E8','pct'=>74,'warna'=>'#1d4ed8'],
                ];
                @endphp
                @foreach($rak as $r)
                <div class="rak-item">
                    <div style="flex:1;min-width:0">
                        <div class="rak-label" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $r['nama'] }}</div>
                        <div class="rak-sub">{{ $r['sub'] }}</div>
                    </div>
                    <div class="rak-bar-wrap">
                        <div class="rak-bar-fill" style="width:{{ $r['pct'] }}%;background:{{ $r['warna'] }}"></div>
                    </div>
                    <div style="font-size:0.72rem;font-weight:700;width:32px;text-align:right;color:{{ $r['warna'] }}">{{ $r['pct'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ Row: Denda + Agenda ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 a5">

        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">💸 Denda Belum Dibayar</span>
                <a href="#" class="lib-link">Kelola →</a>
            </div>
            <div class="lib-card-body">
                @php
                $dendas = [
                    ['nama'=>'Bima Saputra','kelas'=>'7D','buku'=>'Atlas Dunia','hari'=>5,'denda'=>'Rp 2.500','clr'=>'#d97706'],
                    ['nama'=>'Yoga Pratama','kelas'=>'8A','buku'=>'Novel Bumi','hari'=>8,'denda'=>'Rp 4.000','clr'=>'#dc2626'],
                    ['nama'=>'Rena Puspita','kelas'=>'9B','buku'=>'Kamus Bahasa','hari'=>3,'denda'=>'Rp 1.500','clr'=>'#7c3aed'],
                    ['nama'=>'Aldi Firmansyah','kelas'=>'7F','buku'=>'IPS Kelas 7','hari'=>12,'denda'=>'Rp 6.000','clr'=>'#dc2626'],
                ];
                @endphp
                @foreach($dendas as $d)
                <div class="denda-item">
                    <div class="denda-avatar" style="background:{{ $d['clr'] }}">{{ strtoupper(substr($d['nama'],0,1)) }}</div>
                    <div style="flex:1;min-width:0">
                        <div class="denda-name">{{ $d['nama'] }}</div>
                        <div class="denda-kelas">Kelas {{ $d['kelas'] }} &bull; {{ $d['buku'] }} &bull; {{ $d['hari'] }} hari telat</div>
                    </div>
                    <div class="denda-amt">{{ $d['denda'] }}</div>
                </div>
                @endforeach
                <div style="margin-top:12px;padding:10px 14px;background:#fef2f2;border-radius:10px;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:0.8rem;font-weight:600;color:#dc2626">Total Tertunggak</span>
                    <span style="font-family:'Lora',serif;font-size:1rem;font-weight:700;color:#dc2626">Rp 145.500</span>
                </div>
            </div>
        </div>

        <div class="lib-card">
            <div class="lib-card-header">
                <span class="lib-card-title">📅 Agenda Perpustakaan</span>
                <a href="#" class="lib-link">Tambah →</a>
            </div>
            <div class="lib-card-body">
                @php
                $agenda = [
                    ['tgl'=>'25','bln'=>'Nov','judul'=>'Story Telling Hari Guru','desc'=>'Siswa kelas 8 membacakan cerita pendek pilihan'],
                    ['tgl'=>'01','bln'=>'Des','judul'=>'Penerimaan Donasi Buku','desc'=>'Program donasi buku bekas layak pakai'],
                    ['tgl'=>'10','bln'=>'Des','judul'=>'Tutup Sementara — UAS','desc'=>'Perpustakaan tutup selama UAS Ganjil berlangsung'],
                    ['tgl'=>'20','bln'=>'Des','judul'=>'Lomba Resensi Buku','desc'=>'Kompetisi menulis resensi untuk semua jenjang'],
                ];
                @endphp
                @foreach($agenda as $ag)
                <div class="agenda-item">
                    <div class="agenda-date-box">
                        <div class="agenda-date-num">{{ $ag['tgl'] }}</div>
                        <div class="agenda-date-mon">{{ $ag['bln'] }}</div>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="agenda-title">{{ $ag['judul'] }}</div>
                        <div class="agenda-desc">{{ $ag['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
(function() {
    function tick() {
        const now = new Date();
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