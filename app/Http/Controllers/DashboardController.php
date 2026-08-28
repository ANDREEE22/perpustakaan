<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ── Stat Cards ──────────────────────────────────────────
        $totalBuku = Buku::sum('stok');                              // total eksemplar
        $totalJudul = Buku::count();                                  // jumlah judul
        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $pengunjungHariIni = Kunjungan::whereDate('tanggal', $today)->count();
        $totalTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_harus_kembali', '<', $today)
            ->count();

        // ── Peminjaman Terbaru (5 data) ──────────────────────────
        $peminjamanTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Buku Terpopuler (paling banyak dipinjam) ─────────────
        $bukuPopuler = DB::table('peminjaman')
            ->join('bukus', 'peminjaman.buku_id', '=', 'bukus.id')
            ->select('bukus.id', 'bukus.judul', 'bukus.pengarang', DB::raw('COUNT(*) as total_pinjam'))
            ->groupBy('bukus.id', 'bukus.judul', 'bukus.pengarang')
            ->orderByDesc('total_pinjam')
            ->limit(6)
            ->get();

        $maxPinjam = $bukuPopuler->max('total_pinjam') ?: 1;

        // ── Pengunjung 7 hari terakhir (Senin s.d. hari ini) ─────
        $kunjunganMingguan = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tgl = $today->copy()->subDays($i);
            $kunjunganMingguan->push([
                'label' => $tgl->isoFormat('ddd'),   // Sen, Sel, ...
                'jumlah' => Kunjungan::whereDate('tanggal', $tgl)->count(),
                'isToday' => $i === 0,
            ]);
        }
        $totalMingguIni = $kunjunganMingguan->sum('jumlah');
        $maxKunjungan = $kunjunganMingguan->max('jumlah') ?: 1;

        // ── Denda Aktif (dipinjam & terlambat) ───────────────────
        $dendaAktif = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->where('tgl_harus_kembali', '<', $today)
            ->orderBy('tgl_harus_kembali')
            ->limit(5)
            ->get()
            ->map(fn (Peminjaman $p) => $this->mapDendaAktifItem($p, $today));

        $totalDenda = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_harus_kembali', '<', $today)
            ->get()
            ->sum(fn ($p) => ($p->tgl_harus_kembali?->copy()->startOfDay()->diffInDays($today->copy()->startOfDay()) ?? 0) * 500);

        // ── Pengunjung hari ini (buku tamu terbaru) ───────────────
        $kunjunganHariIni = Kunjungan::with('anggota')
            ->whereHas('anggota')
            ->whereDate('tanggal', $today)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalBuku',
            'totalJudul',
            'sedangDipinjam',
            'pengunjungHariIni',
            'totalTerlambat',
            'peminjamanTerbaru',
            'bukuPopuler',
            'maxPinjam',
            'kunjunganMingguan',
            'totalMingguIni',
            'maxKunjungan',
            'dendaAktif',
            'totalDenda',
            'kunjunganHariIni',
        ));
    }

    public function mapDendaAktifItem(Peminjaman $p, Carbon $today): array
    {
        $anggota = $p->anggota;
        $buku = $p->buku;
        $hariTelat = (int) ($p->tgl_harus_kembali?->copy()->startOfDay()->diffInDays($today->copy()->startOfDay()) ?? 0);
        $denda = $hariTelat * 500;

        return [
            'nama' => $anggota?->nama_lengkap ?? 'Anggota tidak tersedia',
            'kelas' => $anggota?->kelas ?? 'Guru',
            'buku' => $buku?->judul ?? 'Judul tidak tersedia',
            'hari' => $hariTelat,
            'denda' => 'Rp '.number_format($denda, 0, ',', '.'),
            'clr' => $hariTelat >= 7 ? '#dc2626' : '#d97706',
            'foto' => $anggota?->foto,
            'inisial' => $anggota?->nama_lengkap ? strtoupper(substr($anggota->nama_lengkap, 0, 1)) : '?',
        ];
    }
}
