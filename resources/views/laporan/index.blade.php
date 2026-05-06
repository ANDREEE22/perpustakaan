<x-layouts::app :title="__('Laporan Peminjaman')">
<div class="flex flex-col gap-6">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Laporan Peminjaman</flux:heading>
            <flux:subheading>
                Periode:
                <strong>{{ $dari->format('d/m/Y') }}</strong>
                s.d.
                <strong>{{ $sampai->format('d/m/Y') }}</strong>
            </flux:subheading>
        </div>

        {{-- Tombol Export --}}
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('laporan.export.pdf', request()->query()) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Unduh PDF
            </a>
            <a href="{{ route('laporan.export.excel', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh Excel
            </a>
            <button onclick="cetakHalaman()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-700 hover:bg-zinc-800 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </button>
        </div>
    </div>

    <flux:separator />

    {{-- ── Filter ── --}}
    <form method="GET" action="{{ route('laporan.index') }}"
          class="flex flex-wrap gap-3 items-end p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">

        <div class="flex flex-col gap-1 flex-1 min-w-[140px]">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari->format('Y-m-d') }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
        </div>

        <div class="flex flex-col gap-1 flex-1 min-w-[140px]">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai->format('Y-m-d') }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
        </div>

        <div class="flex flex-col gap-1 w-full md:w-40">
            <label class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Status</label>
            <select name="status"
                    class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 outline-none focus:border-green-400 transition-colors">
                <option value="">Semua Status</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="kembali"  {{ request('status') === 'kembali'  ? 'selected' : '' }}>Sudah Kembali</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                Tampilkan
            </button>

            {{-- Shortcut bulan --}}
            <div class="flex gap-1">
                <a href="{{ route('laporan.index', ['dari' => now()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Bulan Ini
                </a>
                <a href="{{ route('laporan.index', ['dari' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Bulan Lalu
                </a>
                <a href="{{ route('laporan.index', ['dari' => now()->startOfYear()->format('Y-m-d'), 'sampai' => now()->endOfYear()->format('Y-m-d')]) }}"
                   class="px-3 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400 hover:border-green-400 transition-colors">
                    Tahun Ini
                </a>
            </div>
        </div>
    </form>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4" id="stat-cards">
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-xl">📋</div>
            <div>
                <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ $stats['total'] }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Total Transaksi</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-xl">📤</div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $stats['dipinjam'] }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Sedang Dipinjam</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-xl">✅</div>
            <div>
                <p class="text-2xl font-bold text-green-600">{{ $stats['kembali'] }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Sudah Kembali</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-xl">⏰</div>
            <div>
                <p class="text-2xl font-bold text-red-600">{{ $stats['terlambat'] }}</p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Terlambat</p>
            </div>
        </div>
        <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center text-xl">💰</div>
            <div>
                <p class="text-xl font-bold text-yellow-600">
                    Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-zinc-400 uppercase tracking-wide">Total Denda</p>
            </div>
        </div>
    </div>

    {{-- ── Tabel ── --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden" id="tabel-laporan">

        {{-- Header tabel --}}
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                Ditemukan <span class="text-green-600">{{ $stats['total'] }}</span> transaksi
            </p>
            @if($stats['terlambat'] > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse inline-block"></span>
                    {{ $stats['terlambat'] }} keterlambatan
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">No</th>
                        <th class="px-4 py-3 text-left">Anggota</th>
                        <th class="px-4 py-3 text-left">Judul Buku</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-center">Tgl Pinjam</th>
                        <th class="px-4 py-3 text-center">Harus Kembali</th>
                        <th class="px-4 py-3 text-center">Tgl Kembali</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($peminjaman as $i => $p)
                    @php
                        $dendaAktual = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
                    @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors
                               {{ $p->isTerlambat() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">

                        <td class="px-4 py-3 text-zinc-400 text-xs">{{ $i + 1 }}</td>

                        <td class="px-4 py-3">
                            <div class="font-semibold text-zinc-800 dark:text-zinc-100 text-sm">
                                {{ $p->anggota?->nama_lengkap ?? '—' }}
                            </div>
                            <div class="text-xs text-zinc-400 font-mono">
                                {{ $p->anggota?->nomor_induk ?? '' }}
                                @if($p->anggota?->kelas)
                                    · {{ $p->anggota->kelas }}
                                @endif
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-medium text-zinc-800 dark:text-zinc-100 max-w-[200px] truncate" title="{{ $p->buku?->judul }}">
                                {{ $p->buku?->judul ?? '—' }}
                            </div>
                            <div class="text-xs text-zinc-400">{{ $p->buku?->pengarang ?? '' }}</div>
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 text-xs">
                                {{ $p->buku?->kategori?->nama ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $p->tgl_pinjam?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="text-sm {{ $p->isTerlambat() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">
                                {{ $p->tgl_harus_kembali?->format('d/m/Y') ?? '—' }}
                            </span>
                            @if($p->isTerlambat())
                                <div class="text-xs text-red-500">
                                    +{{ $p->hariTerlambat() }} hari
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $p->tgl_realisasi_kembali?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($p->status === 'kembali')
                                <span class="px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold">
                                    ✅ Kembali
                                </span>
                            @elseif($p->isTerlambat())
                                <span class="px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold animate-pulse">
                                    ⏰ Terlambat
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold">
                                    📤 Dipinjam
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right font-mono text-sm">
                            @if($dendaAktual > 0)
                                <span class="text-red-600 dark:text-red-400 font-semibold">
                                    Rp{{ number_format($dendaAktual, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-zinc-400">
                                <span class="text-4xl">📭</span>
                                <p class="font-medium text-zinc-500">Tidak ada data peminjaman</p>
                                <p class="text-sm">pada periode yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($peminjaman->count() > 0)
                <tfoot class="bg-zinc-50 dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-right text-sm font-semibold text-zinc-600 dark:text-zinc-400">
                            Total Denda:
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-red-600 dark:text-red-400 font-mono">
                            Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Info cetak --}}
    <p class="text-xs text-zinc-400 text-center print:text-[9px]">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB &nbsp;·&nbsp;
        Perpustakaan SMP Negeri 4 Jember
    </p>

</div>

{{-- ══ Print Style & Script ══ --}}
<style>
    @media print {
        body > * { display: none !important; }
        #tabel-laporan,
        #stat-cards {
            display: block !important;
        }
        nav, aside, header, form, button, a {
            display: none !important;
        }
        table { width: 100% !important; font-size: 11px !important; }
        th, td { padding: 4px 6px !important; }
    }
</style>

@pushOnce('scripts')
<script>
(function() {
    window.cetakHalaman = function() {
        const konten = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Peminjaman - SMPN 4 Jember</title>
            <meta charset="utf-8">
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: Arial, sans-serif; font-size: 11px; color: #111; padding: 20px; }

                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a5c3a; padding-bottom: 12px; }
                .header h1 { font-size: 16px; color: #1a5c3a; font-weight: bold; }
                .header p  { font-size: 11px; color: #555; margin-top: 4px; }

                .stats { display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
                .stat  { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; min-width: 100px; }
                .stat .angka { font-size: 20px; font-weight: bold; color: #1a5c3a; }
                .stat .label { font-size: 9px; color: #6b7280; text-transform: uppercase; }

                table { width: 100%; border-collapse: collapse; font-size: 10px; }
                thead th { background: #1a5c3a; color: white; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
                tbody tr:nth-child(even) { background: #f0fdf4; }
                tbody tr.terlambat { background: #fef2f2; color: #991b1b; }
                td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
                tfoot td { font-weight: bold; border-top: 2px solid #d1d5db; background: #fefce8; }

                .badge { display: inline-block; padding: 1px 6px; border-radius: 9999px; font-size: 9px; font-weight: bold; }
                .badge-green  { background: #dcfce7; color: #166534; }
                .badge-amber  { background: #fef3c7; color: #92400e; }
                .badge-red    { background: #fee2e2; color: #991b1b; }

                .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
                @page { margin: 1.5cm; size: A4 landscape; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN PEMINJAMAN BUKU PERPUSTAKAAN</h1>
                <p>SMP Negeri 4 Jember &nbsp;|&nbsp;
                   Periode: {{ $dari->format('d/m/Y') }} s.d. {{ $sampai->format('d/m/Y') }}</p>
            </div>

            <div class="stats">
                <div class="stat"><div class="angka">{{ $stats['total'] }}</div><div class="label">Total Transaksi</div></div>
                <div class="stat"><div class="angka" style="color:#d97706">{{ $stats['dipinjam'] }}</div><div class="label">Dipinjam</div></div>
                <div class="stat"><div class="angka" style="color:#16a34a">{{ $stats['kembali'] }}</div><div class="label">Kembali</div></div>
                <div class="stat"><div class="angka" style="color:#dc2626">{{ $stats['terlambat'] }}</div><div class="label">Terlambat</div></div>
                <div class="stat"><div class="angka" style="color:#ca8a04;font-size:14px">Rp{{ number_format($stats['total_denda'],0,',','.') }}</div><div class="label">Total Denda</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:30px">No</th>
                        <th>Nama Anggota</th>
                        <th>NISN/Kelas</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Tgl Pinjam</th>
                        <th>Harus Kembali</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $i => $p)
                    @php
                        $dendaPrint = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
                    @endphp
                    <tr class="{{ $p->isTerlambat() ? 'terlambat' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $p->anggota?->nama_lengkap ?? '—' }}</strong></td>
                        <td style="font-size:9px">{{ $p->anggota?->nomor_induk ?? '' }}{{ $p->anggota?->kelas ? ' / '.$p->anggota->kelas : '' }}</td>
                        <td>{{ $p->buku?->judul ?? '—' }}</td>
                        <td>{{ $p->buku?->kategori?->nama ?? '—' }}</td>
                        <td>{{ $p->tgl_pinjam?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $p->tgl_harus_kembali?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $p->tgl_realisasi_kembali?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if($p->status === 'kembali')
                                <span class="badge badge-green">Kembali</span>
                            @elseif($p->isTerlambat())
                                <span class="badge badge-red">Terlambat +{{ $p->hariTerlambat() }}h</span>
                            @else
                                <span class="badge badge-amber">Dipinjam</span>
                            @endif
                        </td>
                        <td style="text-align:right">{{ $dendaPrint > 0 ? 'Rp'.number_format($dendaPrint,0,',','.') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" style="text-align:center;padding:20px;color:#9ca3af">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
                @if($peminjaman->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="9" style="text-align:right">Total Denda:</td>
                        <td style="text-align:right;color:#dc2626">Rp{{ number_format($stats['total_denda'],0,',','.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>

            <div class="footer">
                Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB &nbsp;·&nbsp; Perpustakaan SMP Negeri 4 Jember
            </div>
        </body>
        </html>
        `;

        const win = window.open('', '_blank', 'width=1100,height=700');
        win.document.write(konten);
        win.document.close();
        win.focus();
        setTimeout(() => { win.print(); }, 500);
    };
})();
</script>
@endPushOnce

</x-layouts::app>