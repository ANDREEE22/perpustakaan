<x-layouts::app :title="__('Laporan Data Kunjungan')">
<style>
    :root {
        --lib-teal:    #0f766e;
        --lib-emerald: #10b981;
    }
</style>
<div class="flex flex-col gap-6">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Laporan Data Kunjungan</flux:heading>
            <flux:subheading>
                Periode:
                <strong>{{ $tglMulai->format('d/m/Y') }}</strong>
                s.d.
                <strong>{{ $tglAkhir->format('d/m/Y') }}</strong>
            </flux:subheading>
        </div>

        {{-- Tombol Export --}}
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('laporan.kunjungan.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white text-sm font-semibold transition-colors"
               style="background: var(--lib-teal);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh Excel
            </a>
        </div>
    </div>

    <flux:separator />

    {{-- ── Filter ── --}}
    <form method="GET" action="{{ route('laporan.kunjungan') }}"
          class="flex flex-wrap gap-3 items-end p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">

        <div class="flex flex-col gap-1 flex-1 min-w-[140px]">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Dari Tanggal</label>
            <input type="date" name="tgl_mulai" value="{{ $tglMulai->format('Y-m-d') }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
        </div>

        <div class="flex flex-col gap-1 flex-1 min-w-[140px]">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Sampai Tanggal</label>
            <input type="date" name="tgl_akhir" value="{{ $tglAkhir->format('Y-m-d') }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
        </div>

        <div class="flex flex-col gap-1 w-full md:w-40">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Tipe</label>
            <select name="tipe"
                    class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
                <option value="">Semua Tipe</option>
                <option value="siswa" {{ request('tipe') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru"  {{ request('tipe') === 'guru'  ? 'selected' : '' }}>Guru/Staf</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-white text-sm font-semibold transition-colors flex items-center gap-2"
                    style="background: var(--lib-teal);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                Tampilkan
            </button>

            {{-- Shortcut bulan --}}
            <div class="flex gap-1">
                <a href="{{ route('laporan.kunjungan', ['tgl_mulai' => now()->startOfMonth()->format('Y-m-d'), 'tgl_akhir' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Bulan Ini
                </a>
                <a href="{{ route('laporan.kunjungan', ['tgl_mulai' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'tgl_akhir' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Bulan Lalu
                </a>
                <a href="{{ route('laporan.kunjungan', ['tgl_mulai' => now()->startOfYear()->format('Y-m-d'), 'tgl_akhir' => now()->endOfYear()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Tahun Ini
                </a>
            </div>
        </div>
    </form>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ $totalKunjungan }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Total Kunjungan</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600">{{ $totalSiswa }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Kunjungan Siswa</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $totalGuru }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Kunjungan Guru</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-purple-600">{{ $rataRataHarian }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Rata-rata / Hari</p>
            </div>
        </div>
    </div>

    {{-- Grafik + Top Pengunjung ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Grafik harian --}}
        <div class="md:col-span-2 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
            <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 mb-4">Tren Kunjungan Harian</h3>
            <canvas id="trendChart" height="60"></canvas>
        </div>

        {{-- Top pengunjung --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
            <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 mb-4">Top Pengunjung</h3>
            @forelse($topPengunjung as $i => $tp)
            <div class="flex items-center gap-3 pb-3 {{ !$loop->last ? 'border-b border-zinc-200 dark:border-zinc-700' : '' }}">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold text-white" 
                     style="background:{{ $i===0?'#f59e0b':($i===1?'#94a3b8':($i===2?'#b45309':'#a8a29e')) }}">
                    {{ $i+1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 truncate">{{ $tp->anggota->nama_lengkap ?? '—' }}</p>
                    <p class="text-xs text-zinc-400">{{ $tp->anggota->kelas ?? 'Guru/Staf' }}</p>
                </div>
                <p class="text-sm font-bold text-green-600">{{ $tp->total_kunjungan }}x</p>
            </div>
            @empty
            <p class="text-sm text-center text-zinc-400 py-4">Belum ada data</p>
            @endforelse
        </div>
    </div>

    {{-- ── Breakdown keperluan ── --}}
    @if($breakdownKeperluan->isNotEmpty())
    <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 mb-4">Breakdown Keperluan</h3>
        <div class="flex flex-wrap gap-3">
            @php $totalBk = $breakdownKeperluan->sum('jumlah') ?: 1; @endphp
            @foreach($breakdownKeperluan as $bk)
            <div class="flex-1 min-w-[120px] p-3 rounded-xl bg-green-50 dark:bg-green-900/20">
                <p class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">{{ $bk->keperluan ?? 'Umum' }}</p>
                <p class="text-lg font-bold text-green-600 mt-1">{{ $bk->jumlah }}</p>
                <p class="text-xs text-green-600 mt-0.5">{{ round($bk->jumlah/$totalBk*100) }}%</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Tabel detail ── --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="p-5 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">Detail Data Kunjungan</h3>
                <span class="text-xs text-zinc-400">{{ $kunjungan->total() }} catatan</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">No</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">Jam</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">Nama</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">Kelas</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wide">Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $i => $k)
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3 text-xs text-zinc-500 dark:text-zinc-400">{{ $kunjungan->firstItem() + $i }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-800 dark:text-zinc-100">{{ $k->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-zinc-600 dark:text-zinc-400">{{ substr($k->jam_masuk, 0, 5) }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ $k->anggota->nama_lengkap ?? '—' }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $k->anggota->nomor_induk ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-800 dark:text-zinc-100">{{ $k->anggota->kelas ?? 'Guru/Staf' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">{{ $k->keperluan ?? 'Umum' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-sm text-zinc-400">Tidak ada data kunjungan pada periode ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kunjungan->hasPages())
        <div class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $kunjungan->links() }}
        </div>
        @endif
    </div>

</div>
</x-layouts::app>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendChart');
        if (!ctx) return;

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#d4d4d8' : '#52525b';
        const gridColor = isDark ? '#3f3f46' : '#e4e4e7';
        const fillColor = isDark ? '#064e3b' : '#d1fae5';
        const borderColor = '#10b981';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labelGrafik),
                datasets: [{
                    label: 'Kunjungan',
                    data: @json($dataGrafik),
                    borderColor: borderColor,
                    backgroundColor: fillColor,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: borderColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: borderColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#27272a' : '#fff',
                        titleColor: isDark ? '#fafafa' : '#000',
                        bodyColor: isDark ? '#d4d4d8' : '#52525b',
                        borderColor: borderColor,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' kunjungan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12,
                            }
                        }
                    }
                }
            }
        });
    });
</script>
