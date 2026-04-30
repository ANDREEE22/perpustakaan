<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan — tampilkan tabel + filter.
     */
    public function index(Request $request)
    {
        // Default: bulan ini
        $dari  = $request->filled('dari')
            ? Carbon::parse($request->dari)->startOfDay()
            : Carbon::now()->startOfMonth();

        $sampai = $request->filled('sampai')
            ? Carbon::parse($request->sampai)->endOfDay()
            : Carbon::now()->endOfMonth();

        $query = Peminjaman::with(['anggota', 'buku.kategori'])
            ->whereBetween('tgl_pinjam', [$dari, $sampai]);

        // Filter status opsional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tgl_pinjam', 'desc')->get();

        // Ringkasan statistik
        $stats = [
            'total'      => $peminjaman->count(),
            'dipinjam'   => $peminjaman->where('status', 'dipinjam')->count(),
            'kembali'    => $peminjaman->where('status', 'kembali')->count(),
            'terlambat'  => $peminjaman->filter(fn($p) => $p->isTerlambat())->count(),
            'total_denda'=> $peminjaman->sum('denda'),
        ];

        return view('laporan.index', compact('peminjaman', 'stats', 'dari', 'sampai'));
    }

    /**
     * Export ke PDF — pakai DomPDF (sudah include di Laravel).
     */
    public function exportPdf(Request $request)
    {
        $dari   = $request->filled('dari')
            ? Carbon::parse($request->dari)->startOfDay()
            : Carbon::now()->startOfMonth();

        $sampai = $request->filled('sampai')
            ? Carbon::parse($request->sampai)->endOfDay()
            : Carbon::now()->endOfMonth();

        $query = Peminjaman::with(['anggota', 'buku.kategori'])
            ->whereBetween('tgl_pinjam', [$dari, $sampai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tgl_pinjam', 'asc')->get();

        $stats = [
            'total'      => $peminjaman->count(),
            'dipinjam'   => $peminjaman->where('status', 'dipinjam')->count(),
            'kembali'    => $peminjaman->where('status', 'kembali')->count(),
            'terlambat'  => $peminjaman->filter(fn($p) => $p->isTerlambat())->count(),
            'total_denda'=> $peminjaman->sum('denda'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact('peminjaman', 'stats', 'dari', 'sampai'))
            ->setPaper('a4', 'landscape');

        $namaFile = 'laporan-peminjaman-' . $dari->format('Y-m-d') . '-sd-' . $sampai->format('Y-m-d') . '.pdf';

        return $pdf->download($namaFile);
    }

    /**
     * Export ke Excel — pakai Laravel Excel (Maatwebsite).
     */
    public function exportExcel(Request $request)
    {
        $dari   = $request->filled('dari')
            ? Carbon::parse($request->dari)->startOfDay()
            : Carbon::now()->startOfMonth();

        $sampai = $request->filled('sampai')
            ? Carbon::parse($request->sampai)->endOfDay()
            : Carbon::now()->endOfMonth();

        $query = Peminjaman::with(['anggota', 'buku.kategori'])
            ->whereBetween('tgl_pinjam', [$dari, $sampai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('tgl_pinjam', 'asc')->get();

        $namaFile = 'laporan-peminjaman-' . $dari->format('Y-m-d') . '-sd-' . $sampai->format('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanPeminjamanExport($peminjaman, $dari, $sampai),
            $namaFile
        );
    }
}