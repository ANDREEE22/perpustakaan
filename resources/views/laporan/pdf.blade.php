<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1a1a1a; }

        .header { text-align: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #1a5c3a; }
        .header h1 { font-size: 14px; font-weight: bold; color: #1a5c3a; letter-spacing: 0.5px; }
        .header .sub { font-size: 10px; color: #555; margin-top: 4px; }

        .stats { display: flex; gap: 8px; margin-bottom: 14px; }
        .stat-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 7px 10px; text-align: center; }
        .stat-box .angka { font-size: 16px; font-weight: bold; color: #1a5c3a; }
        .stat-box .label { font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; font-size: 9px; }
        thead tr th {
            background-color: #1a5c3a;
            color: white;
            padding: 6px 7px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background-color: #f0fdf4; }
        tbody tr.terlambat { background-color: #fef2f2; }
        td { padding: 5px 7px; vertical-align: top; }

        .badge { display: inline-block; padding: 1px 6px; border-radius: 99px; font-size: 8px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-red   { background: #fee2e2; color: #991b1b; }

        tfoot tr td {
            font-weight: bold;
            border-top: 2px solid #d1d5db;
            background: #fefce8;
            padding: 6px 7px;
        }

        .footer { text-align: center; margin-top: 14px; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: Courier New, monospace; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU PERPUSTAKAAN</h1>
        <div class="sub">
            SMP Negeri 4 Jember
            &nbsp;&bull;&nbsp;
            Periode: {{ $dari->format('d M Y') }} s.d. {{ $sampai->format('d M Y') }}
        </div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="angka">{{ $stats['total'] }}</div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="stat-box">
            <div class="angka" style="color:#d97706">{{ $stats['dipinjam'] }}</div>
            <div class="label">Dipinjam</div>
        </div>
        <div class="stat-box">
            <div class="angka" style="color:#16a34a">{{ $stats['kembali'] }}</div>
            <div class="label">Kembali</div>
        </div>
        <div class="stat-box">
            <div class="angka" style="color:#dc2626">{{ $stats['terlambat'] }}</div>
            <div class="label">Terlambat</div>
        </div>
        <div class="stat-box">
            <div class="angka" style="color:#ca8a04; font-size:11px">
                Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
            </div>
            <div class="label">Total Denda</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:22px">No</th>
                <th style="width:100px">Nama Anggota</th>
                <th style="width:70px">NISN / Kelas</th>
                <th>Judul Buku</th>
                <th style="width:55px">Kategori</th>
                <th style="width:55px" class="text-center">Tgl Pinjam</th>
                <th style="width:58px" class="text-center">Harus Kembali</th>
                <th style="width:58px" class="text-center">Tgl Kembali</th>
                <th style="width:52px" class="text-center">Status</th>
                <th style="width:52px" class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $i => $p)
            @php
                $dendaPdf = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
            @endphp
            <tr class="{{ $p->isTerlambat() ? 'terlambat' : '' }}">
                <td class="text-center" style="color:#9ca3af">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $p->anggota?->nama_lengkap ?? '—' }}</strong>
                </td>
                <td style="font-size:8px; color:#6b7280">
                    {{ $p->anggota?->nomor_induk ?? '—' }}
                    @if($p->anggota?->kelas)
                        <br>Kelas {{ $p->anggota->kelas }}
                    @endif
                </td>
                <td>{{ $p->buku?->judul ?? '—' }}</td>
                <td>{{ $p->buku?->kategori?->nama ?? '—' }}</td>
                <td class="text-center">{{ $p->tgl_pinjam?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-center {{ $p->isTerlambat() ? 'font-bold' : '' }}" style="{{ $p->isTerlambat() ? 'color:#dc2626' : '' }}">
                    {{ $p->tgl_harus_kembali?->format('d/m/Y') ?? '—' }}
                    @if($p->isTerlambat())
                        <br><span style="font-size:8px">+{{ $p->hariTerlambat() }}h</span>
                    @endif
                </td>
                <td class="text-center">{{ $p->tgl_realisasi_kembali?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-center">
                    @if($p->status === 'kembali')
                        <span class="badge badge-green">Kembali</span>
                    @elseif($p->isTerlambat())
                        <span class="badge badge-red">Terlambat</span>
                    @else
                        <span class="badge badge-amber">Dipinjam</span>
                    @endif
                </td>
                <td class="text-right font-mono">
                    @if($dendaPdf > 0)
                        <span style="color:#dc2626; font-weight:bold">
                            Rp{{ number_format($dendaPdf, 0, ',', '.') }}
                        </span>
                    @else
                        <span style="color:#9ca3af">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px; color:#9ca3af">
                    Tidak ada data peminjaman pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($peminjaman->count() > 0)
        <tfoot>
            <tr>
                <td colspan="9" class="text-right">Total Denda Keseluruhan:</td>
                <td class="text-right font-mono" style="color:#dc2626">
                    Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB
        &nbsp;&bull;&nbsp;
        Perpustakaan SMP Negeri 4 Jember
        &nbsp;&bull;&nbsp;
        Dokumen ini digenerate otomatis oleh sistem
    </div>

</body>
</html>