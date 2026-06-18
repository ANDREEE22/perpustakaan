<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKunjunganController extends Controller
{
    /**
     * Halaman laporan data kunjungan.
     * Filter: rentang tanggal, kelas/tipe anggota, keperluan.
     */
    public function index(Request $request)
    {
        // Default periode: bulan ini
        $tglMulai  = $request->filled('tgl_mulai')
            ? Carbon::parse($request->tgl_mulai)
            : Carbon::now()->startOfMonth();

        $tglAkhir  = $request->filled('tgl_akhir')
            ? Carbon::parse($request->tgl_akhir)
            : Carbon::now()->endOfMonth();

        $query = Kunjungan::with('anggota')
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglAkhir);

        // Filter tipe: siswa / guru
        if ($request->filled('tipe')) {
            if ($request->tipe === 'siswa') {
                $query->whereHas('anggota', fn($a) => $a->whereNotNull('kelas')->where('kelas', '!=', ''));
            } elseif ($request->tipe === 'guru') {
                $query->whereHas('anggota', fn($a) => $a->whereNull('kelas')->orWhere('kelas', ''));
            }
        }

        // Filter keperluan
        if ($request->filled('keperluan')) {
            $query->where('keperluan', $request->keperluan);
        }

        // Search nama / nisn
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function ($a) use ($search) {
                $a->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%");
            });
        }

        $kunjungan = $query->orderBy('tanggal', 'desc')
                           ->orderBy('jam_masuk', 'desc')
                           ->paginate(20)
                           ->withQueryString();

        // ── Statistik ringkas periode ini ─────────────────────
        $statsQuery = Kunjungan::whereDate('tanggal', '>=', $tglMulai)
                                ->whereDate('tanggal', '<=', $tglAkhir);

        $totalKunjungan = $statsQuery->count();

        $totalSiswa = (clone $statsQuery)
            ->whereHas('anggota', fn($a) => $a->whereNotNull('kelas')->where('kelas', '!=', ''))
            ->count();

        $totalGuru = $totalKunjungan - $totalSiswa;

        $rataRataHarian = $tglMulai->diffInDays($tglAkhir) > 0
            ? round($totalKunjungan / ($tglMulai->diffInDays($tglAkhir) + 1), 1)
            : $totalKunjungan;

        // ── Grafik: kunjungan per hari dalam periode ──────────
        $grafikHarian = Kunjungan::select(
                DB::raw('DATE(tanggal) as tgl'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglAkhir)
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        $labelGrafik = collect();
        $dataGrafik  = collect();
        $cursor      = $tglMulai->copy();
        while ($cursor->lte($tglAkhir) && $labelGrafik->count() < 31) {
            $key = $cursor->format('Y-m-d');
            $labelGrafik->push($cursor->format('d/m'));
            $dataGrafik->push($grafikHarian->get($key)?->jumlah ?? 0);
            $cursor->addDay();
        }

        // ── Top 5 pengunjung terbanyak ──────────────────────────
        $topPengunjung = Kunjungan::select('anggota_id', DB::raw('COUNT(*) as total_kunjungan'))
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglAkhir)
            ->groupBy('anggota_id')
            ->orderByDesc('total_kunjungan')
            ->limit(5)
            ->with('anggota')
            ->get();

        // ── Breakdown keperluan ──────────────────────────────────
        $breakdownKeperluan = Kunjungan::select('keperluan', DB::raw('COUNT(*) as jumlah'))
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglAkhir)
            ->groupBy('keperluan')
            ->orderByDesc('jumlah')
            ->get();

        return view('laporan.kunjungan', compact(
            'kunjungan',
            'tglMulai',
            'tglAkhir',
            'totalKunjungan',
            'totalSiswa',
            'totalGuru',
            'rataRataHarian',
            'labelGrafik',
            'dataGrafik',
            'topPengunjung',
            'breakdownKeperluan',
        ));
    }

    /**
     * Export laporan kunjungan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $tglMulai = $request->filled('tgl_mulai') ? Carbon::parse($request->tgl_mulai) : Carbon::now()->startOfMonth();
        $tglAkhir = $request->filled('tgl_akhir')  ? Carbon::parse($request->tgl_akhir)  : Carbon::now()->endOfMonth();

        $data = Kunjungan::with('anggota')
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglAkhir)
            ->orderBy('tanggal')
            ->orderBy('jam_masuk')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kunjungan');

        // Header
        $headers = ['No', 'Tanggal', 'Jam Masuk', 'NISN/NIP', 'Nama', 'Kelas', 'Keperluan'];
        foreach ($headers as $col => $h) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
            $cell->setValue($h);
            $sheet->getStyleByColumnAndRow($col + 1, 1)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1f2937']],
            ]);
            $sheet->getColumnDimensionByColumn($col + 1)->setWidth(18);
        }

        // Data
        foreach ($data as $i => $k) {
            $row = $i + 2;
            $sheet->getCellByColumnAndRow(1, $row)->setValue($i + 1);
            $sheet->getCellByColumnAndRow(2, $row)->setValue($k->tanggal->format('d/m/Y'));
            $sheet->getCellByColumnAndRow(3, $row)->setValue(substr($k->jam_masuk, 0, 5));
            $sheet->getCellByColumnAndRow(4, $row)->setValue($k->anggota->nomor_induk ?? '-');
            $sheet->getCellByColumnAndRow(5, $row)->setValue($k->anggota->nama_lengkap ?? '-');
            $sheet->getCellByColumnAndRow(6, $row)->setValue($k->anggota->kelas ?? 'Guru/Staf');
            $sheet->getCellByColumnAndRow(7, $row)->setValue($k->keperluan ?? 'Umum');
        }

        $filename = 'laporan-kunjungan-' . $tglMulai->format('Ymd') . '-' . $tglAkhir->format('Ymd') . '.xlsx';
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $path     = storage_path('app/' . $filename);
        $writer->save($path);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}