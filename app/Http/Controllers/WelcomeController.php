<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $totalJudul = Buku::count();
        $judulTersedia = Buku::where('stok', '>', 0)->count();
        $kategori = Kategori::orderBy('nama')->pluck('nama');
        $kunjunganMingguan = $this->weeklyVisitSeries($today);
        $grafikKunjungan = $this->lineChartPoints(
            series: $kunjunganMingguan,
            maxValue: (int) ($kunjunganMingguan->max('jumlah') ?: 1),
        );

        $grafikKunjunganPolyline = $grafikKunjungan
            ->map(fn (array $point): string => "{$point['x']},{$point['y']}")
            ->implode(' ');

        $grafikKunjunganArea = $grafikKunjungan->isEmpty()
            ? ''
            : $grafikKunjungan->first()['x'].',96 '.$grafikKunjunganPolyline.' '.$grafikKunjungan->last()['x'].',96';

        $stats = [
            'total_koleksi' => (int) Buku::sum('stok'),
            'dipinjam_bulan_ini' => Peminjaman::whereMonth('tgl_pinjam', $today->month)
                ->whereYear('tgl_pinjam', $today->year)
                ->count(),
            'total_kategori' => $kategori->count(),
            'persen_tersedia' => $totalJudul > 0 ? (int) round(($judulTersedia / $totalJudul) * 100) : 0,
            'kunjungan_minggu_ini' => $kunjunganMingguan->sum('jumlah'),
        ];

        $dataBuku = Buku::with('kategori')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (Buku $buku): array => [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->pengarang,
                'kategori' => $buku->kategori?->nama ?? 'Tanpa Kategori',
                'tahun' => $buku->tahun_terbit ?? '-',
                'penerbit' => $buku->penerbit ?? '-',
                'status' => $buku->stok > 0 ? 'tersedia' : 'dipinjam',
                'kode' => $this->bookInitials($buku->judul),
                'warna' => $this->bookGradient($buku->kategori?->nama),
                'deskripsi' => $buku->description ?: 'Deskripsi buku belum tersedia.',
                'sampul' => $buku->sampul ? asset('storage/'.$buku->sampul) : null,
            ])
            ->values();

        return view('welcome', compact(
            'dataBuku',
            'kategori',
            'stats',
            'grafikKunjungan',
            'grafikKunjunganPolyline',
            'grafikKunjunganArea',
        ));
    }

    /**
     * @return Collection<int, array{label: string, tanggal: string, jumlah: int, isToday: bool}>
     */
    private function weeklyVisitSeries(Carbon $today): Collection
    {
        $startDate = $today->copy()->subDays(6);

        $visitsByDate = Kunjungan::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah')
            ->whereBetween('tanggal', [$startDate->toDateString(), $today->toDateString()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('jumlah', 'tanggal');

        return collect(range(6, 0))->map(function (int $daysAgo) use ($today, $visitsByDate): array {
            $date = $today->copy()->subDays($daysAgo);
            $key = $date->toDateString();

            return [
                'label' => $date->isoFormat('ddd'),
                'tanggal' => $date->isoFormat('D MMM'),
                'jumlah' => (int) ($visitsByDate[$key] ?? 0),
                'isToday' => $daysAgo === 0,
            ];
        });
    }

    /**
     * @param  Collection<int, array{label: string, tanggal: string, jumlah: int, isToday: bool}>  $series
     * @return Collection<int, array{label: string, tanggal: string, jumlah: int, isToday: bool, x: float, y: float}>
     */
    private function lineChartPoints(Collection $series, int $maxValue): Collection
    {
        $count = max(1, $series->count() - 1);
        $safeMaxValue = max(1, $maxValue);

        return $series->values()->map(function (array $point, int $index) use ($count, $safeMaxValue): array {
            $x = 4 + ($index * (92 / $count));
            $y = 92 - (($point['jumlah'] / $safeMaxValue) * 84);

            return [
                ...$point,
                'x' => round($x, 2),
                'y' => round($y, 2),
            ];
        });
    }

    private function bookInitials(string $title): string
    {
        return Str::of($title)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word): string => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    private function bookGradient(?string $categoryName): string
    {
        return match ($categoryName) {
            'Pelajaran' => 'linear-gradient(135deg, #14532d, #14b8a6)',
            'Fiksi' => 'linear-gradient(135deg, #92400e, #f59e0b)',
            'Sains' => 'linear-gradient(135deg, #155e75, #38bdf8)',
            'Sejarah' => 'linear-gradient(135deg, #7c2d12, #fdba74)',
            'Agama' => 'linear-gradient(135deg, #166534, #84cc16)',
            'Bahasa' => 'linear-gradient(135deg, #be123c, #fb7185)',
            default => 'linear-gradient(135deg, #0f172a, #22d3ee)',
        };
    }
}
