<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5px; color: #222; line-height: 1.4; }
        
        @page {
            size: A4 landscape;
            margin: 10mm 12mm;
        }

        .header { 
            text-align: center; 
            margin-bottom: 10px; 
            padding-bottom: 6px; 
            border-bottom: 1.5px solid #0f766e;
        }
        .header h1 { 
            font-size: 12px; 
            font-weight: bold; 
            color: #0f766e; 
            letter-spacing: 0.3px;
            margin: 0 0 2px 0;
        }
        .header .sub { 
            font-size: 8px; 
            color: #666;
            margin: 0;
        }

        .stats { 
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .stat-box { 
            display: table-cell;
            width: 20%;
            padding: 8px 4px;
            text-align: center;
            border: 1px solid #e0e0e0;
            background: #fafafa;
        }
        .stat-box .angka { 
            font-size: 14px; 
            font-weight: bold; 
            color: #0f766e;
            display: block;
            margin-bottom: 3px;
        }
        .stat-box .label { 
            font-size: 7px; 
            color: #888; 
            text-transform: uppercase;
            letter-spacing: 0.2px;
            font-weight: 500;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 8.5px;
        }
        thead tr th {
            background-color: #0f766e;
            color: white;
            padding: 5px 4px;
            text-align: left;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            font-weight: 600;
            border: none;
        }
        tbody tr { 
            border-bottom: 0.5px solid #e8e8e8;
            height: 18px;
        }
        tbody tr:nth-child(even) { 
            background-color: #fcfcfc;
        }
        tbody tr.terlambat { 
            background-color: #fff5f5;
        }
        td { 
            padding: 3px 4px; 
            vertical-align: middle;
            border: none;
        }

        .badge { 
            display: inline-block; 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-size: 7px; 
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-green { background: #d1f0e3; color: #0f5c47; }
        .badge-amber { background: #fef0d9; color: #8a6700; }
        .badge-red   { background: #ffe8e6; color: #b91515; }

        tfoot tr td {
            font-weight: 600;
            border-top: 1px solid #d0d0d0;
            background: #f5f5f5;
            padding: 5px 4px;
            font-size: 8.5px;
        }

        .footer { 
            text-align: center; 
            margin-top: 8px; 
            font-size: 7px; 
            color: #999; 
            border-top: 0.5px solid #e0e0e0; 
            padding-top: 4px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: Courier New, monospace; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU PERPUSTAKAAN</h1>
        <div class="sub">SMP Negeri 4 Jember — Periode: {{ $dari->format('d M Y') }} s.d. {{ $sampai->format('d M Y') }}</div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <span class="angka">{{ $stats['total'] }}</span>
            <span class="label">Total Transaksi</span>
        </div>
        <div class="stat-box">
            <span class="angka" style="color:#d97706">{{ $stats['dipinjam'] }}</span>
            <span class="label">Dipinjam</span>
        </div>
        <div class="stat-box">
            <span class="angka" style="color:#10b981">{{ $stats['kembali'] }}</span>
            <span class="label">Kembali</span>
        </div>
        <div class="stat-box">
            <span class="angka" style="color:#dc2626">{{ $stats['terlambat'] }}</span>
            <span class="label">Terlambat</span>
        </div>
        <div class="stat-box">
            <span class="angka" style="color:#8b6f00; font-size:14px">Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}</span>
            <span class="label">Total Denda</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:18px">No</th>
                <th style="width:90px">Nama Anggota</th>
                <th style="width:60px">NISN / Kelas</th>
                <th style="flex-grow:1">Judul Buku</th>
                <th style="width:50px">Kategori</th>
                <th style="width:50px" class="text-center">Tgl Pinjam</th>
                <th style="width:55px" class="text-center">Harus Kembali</th>
                <th style="width:50px" class="text-center">Tgl Kembali</th>
                <th style="width:50px" class="text-center">Status</th>
                <th style="width:50px" class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $i => $p)
            @php
                $dendaPdf = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
            @endphp
            <tr class="{{ $p->isTerlambat() ? 'terlambat' : '' }}">
                <td class="text-center" style="color:#aaa">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $p->anggota?->nama_lengkap ?? '—' }}</strong>
                </td>
                <td style="font-size:8px; color:#777">
                    {{ $p->anggota?->nomor_induk ?? '—' }}
                    @if($p->anggota?->kelas)
                        / {{ $p->anggota->kelas }}
                    @endif
                </td>
                <td style="font-size:8.5px">{{ $p->buku?->judul ?? '—' }}</td>
                <td style="font-size:8px">{{ $p->buku?->kategori?->nama ?? '—' }}</td>
                <td class="text-center">{{ $p->tgl_pinjam?->format('d/m/Y') ?? '—' }}</td>
                <td class="text-center {{ $p->isTerlambat() ? 'font-bold' : '' }}" style="{{ $p->isTerlambat() ? 'color:#dc2626' : '' }}">
                    {{ $p->tgl_harus_kembali?->format('d/m/Y') ?? '—' }}
                    @if($p->isTerlambat())
                        <br><span style="font-size:7.5px">+{{ $p->hariTerlambat() }}h</span>
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
                        <strong style="color:#dc2626">Rp{{ number_format($dendaPdf, 0, ',', '.') }}</strong>
                    @else
                        <span style="color:#bbb">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 15px; color:#999">
                    Tidak ada data peminjaman pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($peminjaman->count() > 0)
        <tfoot>
            <tr>
                <td colspan="9" class="text-right">Total Denda Keseluruhan:</td>
                <td class="text-right font-mono" style="color:#dc2626; font-weight:bold">
                    Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak {{ now()->format('d/m/Y H:i') }} WIB • Perpustakaan SMP Negeri 4 Jember • Dokumen otomatis
    </div>

</body>
</html>