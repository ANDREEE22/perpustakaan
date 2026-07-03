<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPeminjamanExport;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan — tampilkan tabel + filter.
     */
    public function index(Request $request)
    {
        $dari = $request->filled('dari')
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

        $raw = $query->orderBy('tgl_pinjam', 'desc')->get();
        $peminjaman = $this->groupPeminjaman($raw);

        $stats = [
            'total' => $peminjaman->count(),
            'dipinjam' => $peminjaman->where(fn ($group) => $group->first()->status === 'dipinjam')->count(),
            'kembali' => $peminjaman->where(fn ($group) => $group->first()->status === 'kembali')->count(),
            'terlambat' => $peminjaman->filter(fn ($group) => $group->first()->isTerlambat())->count(),
            'total_denda' => $peminjaman->sum(fn ($group) => $group->sum(fn ($p) => $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda)),
        ];

        return view('laporan.index', compact('peminjaman', 'stats', 'dari', 'sampai'));
    }

    /**
     * Export ke PDF — pakai DomPDF (sudah include di Laravel).
     */
    public function exportPdf(Request $request)
    {
        $dari = $request->filled('dari')
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

        $raw = $query->orderBy('tgl_pinjam', 'asc')->get();
        $peminjaman = $this->groupPeminjaman($raw);

        $stats = [
            'total' => $peminjaman->count(),
            'dipinjam' => $peminjaman->where(fn ($group) => $group->first()->status === 'dipinjam')->count(),
            'kembali' => $peminjaman->where(fn ($group) => $group->first()->status === 'kembali')->count(),
            'terlambat' => $peminjaman->filter(fn ($group) => $group->first()->isTerlambat())->count(),
            'total_denda' => $peminjaman->sum(fn ($group) => $group->sum(fn ($p) => $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda)),
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('peminjaman', 'stats', 'dari', 'sampai'))
            ->setPaper('a4', 'landscape');

        $namaFile = 'laporan-peminjaman-'.$dari->format('Y-m-d').'-sd-'.$sampai->format('Y-m-d').'.pdf';

        return $pdf->download($namaFile);
    }

    /**
     * Export ke Excel — pakai Laravel Excel (Maatwebsite).
     */
    public function exportExcel(Request $request)
    {
        $dari = $request->filled('dari')
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

        $raw = $query->orderBy('tgl_pinjam', 'asc')->get();
        $peminjaman = $this->groupPeminjaman($raw);

        $namaFile = 'laporan-peminjaman-'.$dari->format('Y-m-d').'-sd-'.$sampai->format('Y-m-d').'.xlsx';

        return Excel::download(
            new LaporanPeminjamanExport($peminjaman, $dari, $sampai),
            $namaFile
        );
    }

    private function groupPeminjaman($peminjaman)
    {
        return $peminjaman->groupBy(function ($p) {
            return $p->anggota_id
                .'|'.($p->tgl_pinjam?->format('Y-m-d') ?? '')
                .'|'.($p->tgl_harus_kembali?->format('Y-m-d') ?? '')
                .'|'.$p->status;
        })->map(fn ($group) => $group->values())->values();
    }
}
