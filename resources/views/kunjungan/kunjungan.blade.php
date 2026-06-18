<x-layouts::app :title="__('Laporan Data Kunjungan')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --lk-surface: #fff;
    --lk-border:  #e7e2da;
    --lk-ink:     #1c1917;
    --lk-muted:   #78716c;
    --lk-emerald: #10b981;
    --lk-cream:   #f0fdf4;
}
.dark {
    --lk-surface: #1f2937;
    --lk-border:  #374151;
    --lk-ink:     #fafaf9;
    --lk-muted:   #a8a29e;
    --lk-cream:   #14532d;
}
.lk-root { font-family: 'DM Sans', sans-serif; }

.lk-card { background: var(--lk-surface); border: 1.5px solid var(--lk-border); border-radius: 18px; overflow: hidden; }
.lk-card-header { padding: 16px 20px; border-bottom: 1.5px solid var(--lk-border); display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; }
.lk-card-title  { font-family: 'Lora', serif; font-size: 0.95rem; font-weight: 600; color: var(--lk-ink); }
.lk-card-body   { padding: 16px 20px; }

.lk-stat-card { background: var(--lk-surface); border: 1.5px solid var(--lk-border); border-radius: 18px; padding: 18px; position: relative; overflow: hidden; }
.lk-stat-accent { position: absolute; top: 0; left: 0; right: 0; height: 4px; }
.lk-stat-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 12px; }
.lk-stat-num { font-family: 'Lora', serif; font-size: 1.9rem; font-weight: 700; color: var(--lk-ink); line-height: 1; }
.lk-stat-lbl { font-size: 0.72rem; font-weight: 600; color: var(--lk-muted); text-transform: uppercase; letter-spacing: 0.04em; margin-top: 6px; }

/* Sparkline grafik harian */
.chart-wrap { display: flex; align-items: flex-end; gap: 3px; height: 110px; overflow-x: auto; padding-bottom: 4px; }
.chart-bar  { flex-shrink: 0; width: 18px; border-radius: 4px 4px 0 0; background: linear-gradient(180deg, #10b981, #6ee7b7); transition: opacity 0.15s; cursor: pointer; position: relative; }
.chart-bar:hover { opacity: 0.75; }
.chart-labels { display: flex; gap: 3px; margin-top: 4px; overflow-x: auto; }
.chart-label  { flex-shrink: 0; width: 18px; font-size: 0.55rem; text-align: center; color: var(--lk-muted); writing-mode: vertical-rl; text-orientation: mixed; height: 30px; }

/* Top pengunjung */
.top-item { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--lk-border); }
.top-item:last-child { border-bottom: none; }
.top-rank { font-family: 'Lora', serif; font-weight: 700; width: 24px; text-align: center; flex-shrink: 0; }

/* Filter bar */
.lk-input  { padding: 9px 12px; border: 1.5px solid var(--lk-border); border-radius: 10px; font-size: 0.82rem; background: var(--lk-surface); color: var(--lk-ink); outline: none; font-family: 'DM Sans', sans-serif; }
.lk-input:focus { border-color: var(--lk-emerald); }
.lk-select { padding: 9px 32px 9px 12px; border: 1.5px solid var(--lk-border); border-radius: 10px; font-size: 0.82rem; background: var(--lk-surface); color: var(--lk-ink); outline: none; appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a8a29e' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; }
.lk-select:focus { border-color: var(--lk-emerald); }

@keyframes lkFadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
.la1 { animation: lkFadeUp 0.35s 0.00s ease both; }
.la2 { animation: lkFadeUp 0.35s 0.06s ease both; }
.la3 { animation: lkFadeUp 0.35s 0.12s ease both; }
.la4 { animation: lkFadeUp 0.35s 0.18s ease both; }
</style>

<div class="lk-root flex flex-col gap-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4 la1">
        <div>
            <h1 class="text-2xl font-bold tracking-tight" style="font-family:'Lora',serif;color:var(--lk-ink)">📊 Laporan Data Kunjungan</h1>
            <p class="text-sm mt-0.5" style="color:var(--lk-muted)">Rekap kunjungan buku tamu perpustakaan SMPN 4 Jember</p>
        </div>
        <a href="{{ route('laporan.kunjungan.export') }}?{{ request()->getQueryString() }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-colors"
           style="background:#10b981" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('laporan.kunjungan') }}" class="lk-card la1">
        <div class="lk-card-body flex flex-wrap gap-3 items-end">
            <div>
                <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 block mb-1.5">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai->format('Y-m-d') }}" class="lk-input">
            </div>
            <div>
                <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 block mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" value="{{ $tglAkhir->format('Y-m-d') }}" class="lk-input">
            </div>
            <div>
                <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 block mb-1.5">Tipe</label>
                <select name="tipe" class="lk-select">
                    <option value="">Semua Tipe</option>
                    <option value="siswa" {{ request('tipe')=='siswa'?'selected':'' }}>Siswa</option>
                    <option value="guru"  {{ request('tipe')=='guru' ?'selected':'' }}>Guru/Staf</option>
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 block mb-1.5">Cari Nama/NISN</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NISN..." class="lk-input w-full">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#10b981">
                Terapkan
            </button>
            @if(request()->hasAny(['tgl_mulai','tgl_akhir','tipe','search','keperluan']))
            <a href="{{ route('laporan.kunjungan') }}" class="px-4 py-2.5 rounded-xl border text-sm font-semibold text-zinc-500" style="border-color:var(--lk-border)">Reset</a>
            @endif
        </div>
    </form>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 la2">
        <div class="lk-stat-card">
            <div class="lk-stat-accent" style="background:#10b981"></div>
            <div class="lk-stat-icon" style="background:#ecfdf5;color:#059669">📋</div>
            <div class="lk-stat-num">{{ $totalKunjungan }}</div>
            <div class="lk-stat-lbl">Total Kunjungan</div>
        </div>
        <div class="lk-stat-card">
            <div class="lk-stat-accent" style="background:#3b82f6"></div>
            <div class="lk-stat-icon" style="background:#eff6ff;color:#1d4ed8">🎓</div>
            <div class="lk-stat-num">{{ $totalSiswa }}</div>
            <div class="lk-stat-lbl">Kunjungan Siswa</div>
        </div>
        <div class="lk-stat-card">
            <div class="lk-stat-accent" style="background:#f59e0b"></div>
            <div class="lk-stat-icon" style="background:#fffbeb;color:#b45309">👨‍🏫</div>
            <div class="lk-stat-num">{{ $totalGuru }}</div>
            <div class="lk-stat-lbl">Kunjungan Guru</div>
        </div>
        <div class="lk-stat-card">
            <div class="lk-stat-accent" style="background:#8b5cf6"></div>
            <div class="lk-stat-icon" style="background:#f5f3ff;color:#7c3aed">📈</div>
            <div class="lk-stat-num">{{ $rataRataHarian }}</div>
            <div class="lk-stat-lbl">Rata-rata / Hari</div>
        </div>
    </div>

    {{-- Grafik + Top Pengunjung --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 la3">

        {{-- Grafik harian --}}
        <div class="lk-card md:col-span-2">
            <div class="lk-card-header">
                <span class="lk-card-title">📈 Tren Kunjungan Harian</span>
                <span class="text-xs text-zinc-400">{{ $tglMulai->format('d M Y') }} – {{ $tglAkhir->format('d M Y') }}</span>
            </div>
            <div class="lk-card-body">
                <div class="chart-wrap">
                    @php $maxData = $dataGrafik->max() ?: 1; @endphp
                    @foreach($dataGrafik as $i => $val)
                    <div class="chart-bar" style="height:{{ max(4, round($val/$maxData*100)) }}%" title="{{ $labelGrafik[$i] }}: {{ $val }} kunjungan"></div>
                    @endforeach
                </div>
                <div class="chart-labels">
                    @foreach($labelGrafik as $lbl)
                    <div class="chart-label">{{ $lbl }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top pengunjung --}}
        <div class="lk-card">
            <div class="lk-card-header">
                <span class="lk-card-title">🏆 Top Pengunjung</span>
            </div>
            <div class="lk-card-body">
                @forelse($topPengunjung as $i => $tp)
                <div class="top-item">
                    <div class="top-rank" style="color:{{ $i===0?'#f59e0b':($i===1?'#94a3b8':($i===2?'#b45309':'#a8a29e')) }}">
                        {{ $i+1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold truncate" style="color:var(--lk-ink)">{{ $tp->anggota->nama_lengkap ?? '—' }}</div>
                        <div class="text-xs" style="color:var(--lk-muted)">{{ $tp->anggota->kelas ?? 'Guru/Staf' }}</div>
                    </div>
                    <div class="text-sm font-bold" style="color:#10b981">{{ $tp->total_kunjungan }}x</div>
                </div>
                @empty
                <p class="text-sm text-center py-6" style="color:var(--lk-muted)">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Breakdown keperluan --}}
    @if($breakdownKeperluan->isNotEmpty())
    <div class="lk-card la3">
        <div class="lk-card-header"><span class="lk-card-title">🗂️ Breakdown Keperluan</span></div>
        <div class="lk-card-body flex flex-wrap gap-3">
            @php $totalBk = $breakdownKeperluan->sum('jumlah') ?: 1; @endphp
            @foreach($breakdownKeperluan as $bk)
            <div class="flex-1 min-w-[140px] p-3 rounded-xl" style="background:var(--lk-cream)">
                <div class="text-xs font-semibold" style="color:var(--lk-muted)">{{ $bk->keperluan ?? 'Umum' }}</div>
                <div class="text-lg font-bold" style="font-family:'Lora',serif;color:var(--lk-ink)">{{ $bk->jumlah }}</div>
                <div class="text-xs" style="color:#10b981">{{ round($bk->jumlah/$totalBk*100) }}%</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tabel detail --}}
    <div class="lk-card la4">
        <div class="lk-card-header">
            <span class="lk-card-title">📄 Detail Data Kunjungan</span>
            <span class="text-xs text-zinc-400">{{ $kunjungan->total() }} catatan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1.5px solid var(--lk-border)">
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">No</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">Jam</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">Nama</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">Kelas</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color:var(--lk-muted)">Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $i => $k)
                    <tr style="border-bottom:1px solid var(--lk-border)">
                        <td class="px-4 py-3 text-xs" style="color:var(--lk-muted)">{{ $kunjungan->firstItem() + $i }}</td>
                        <td class="px-4 py-3 text-sm" style="color:var(--lk-ink)">{{ $k->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm font-mono" style="color:var(--lk-muted)">{{ substr($k->jam_masuk, 0, 5) }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold" style="color:var(--lk-ink)">{{ $k->anggota->nama_lengkap ?? '—' }}</div>
                            <div class="text-xs" style="color:var(--lk-muted)">{{ $k->anggota->nomor_induk ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color:var(--lk-ink)">{{ $k->anggota->kelas ?? 'Guru/Staf' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#ecfdf5;color:#065f46">{{ $k->keperluan ?? 'Umum' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-10 text-sm" style="color:var(--lk-muted)">Tidak ada data kunjungan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kunjungan->hasPages())
        <div class="px-4 py-3" style="border-top:1.5px solid var(--lk-border)">{{ $kunjungan->links() }}</div>
        @endif
    </div>

</div>
</x-layouts::app>