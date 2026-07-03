<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku'])
            ->orderByRaw("FIELD(status, 'dipinjam', 'kembali')")
            ->orderBy('tgl_harus_kembali', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('filter') && $request->filter === 'terlambat') {
            $query->where('status', 'dipinjam')
                ->where('tgl_harus_kembali', '<', Carbon::today());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('anggota', fn ($a) => $a->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_induk', 'like', "%{$search}%"))
                    ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->paginate(15)->withQueryString();

        // Agregasi: gabungkan baris peminjaman yang merupakan bagian dari transaksi
        // yang sama (anggota + tanggal pinjam + tanggal harus kembali + status)
        $grouped = $peminjaman->getCollection()
            ->groupBy(function ($p) {
                return $p->anggota_id
                    .'|'.($p->tgl_pinjam?->format('Y-m-d') ?? '')
                    .'|'.($p->tgl_harus_kembali?->format('Y-m-d') ?? '')
                    .'|'.$p->status;
            })
            ->map(function ($group) {
                return $group->values(); // collection of Peminjaman models
            })->values();

        // Beri nama baru untuk koleksi teragregasi yang akan dipakai di view
        $peminjamanGrouped = $grouped;

        $stats = [
            'total' => Peminjaman::count(),
            'aktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'kembali' => Peminjaman::where('status', 'kembali')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->where('tgl_harus_kembali', '<', Carbon::today())
                ->count(),
        ];

        return view('pinjam.index', compact('peminjaman', 'peminjamanGrouped', 'stats'));
    }

    public function create()
    {
        return view('pinjam.create');
    }

    public function store(Request $request)
    {
        if ($request->filled('items')) {
            $validated = $request->validate([
                'anggota_id' => 'required|exists:anggotas,id',
                'items' => 'required|array|min:1',
                'items.*.buku_id' => 'required|distinct|exists:bukus,id',
                'items.*.qty' => 'required|integer|min:1',
                'tgl_pinjam' => 'required|date',
                'tgl_harus_kembali' => 'required|date|after_or_equal:tgl_pinjam',
                'catatan' => 'nullable|string|max:255',
            ]);

            $items = collect($validated['items'])
                ->mapWithKeys(fn ($item) => [$item['buku_id'] => $item['qty']]);
        } else {
            $validated = $request->validate([
                'anggota_id' => 'required|exists:anggotas,id',
                'buku_id' => 'required|array|min:1',
                'buku_id.*' => 'required|distinct|exists:bukus,id',
                'tgl_pinjam' => 'required|date',
                'tgl_harus_kembali' => 'required|date|after_or_equal:tgl_pinjam',
                'catatan' => 'nullable|string|max:255',
            ]);

            $items = collect($validated['buku_id'])
                ->mapWithKeys(fn ($bukuId) => [$bukuId => 1]);
        }

        $bukus = Buku::whereIn('id', $items->keys())
            ->get()
            ->keyBy('id');

        try {
            DB::transaction(function () use ($validated, $bukus, $items) {
                foreach ($items as $bukuId => $qty) {
                    $buku = $bukus->get($bukuId);

                    if (! $buku) {
                        throw new \RuntimeException('Buku tidak ditemukan.');
                    }

                    if ($buku->stok < $qty) {
                        throw new \RuntimeException('Stok buku "'.$buku->judul.'" tidak mencukupi. Tersedia: '.$buku->stok.'.');
                    }

                    $sudahPinjam = Peminjaman::where('anggota_id', $validated['anggota_id'])
                        ->where('buku_id', $bukuId)
                        ->where('status', 'dipinjam')
                        ->exists();

                    if ($sudahPinjam) {
                        throw new \RuntimeException('Anggota ini masih meminjam buku "'.$buku->judul.'".');
                    }

                    for ($i = 0; $i < $qty; $i++) {
                        Peminjaman::create([
                            'anggota_id' => $validated['anggota_id'],
                            'buku_id' => $bukuId,
                            'tgl_pinjam' => $validated['tgl_pinjam'],
                            'tgl_harus_kembali' => $validated['tgl_harus_kembali'],
                            'catatan' => $validated['catatan'] ?? null,
                            'status' => 'dipinjam',
                            'denda' => 0,
                        ]);
                    }

                    $buku->decrement('stok', $qty);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['buku_id' => $e->getMessage()])->withInput();
        }

        $totalBuku = $items->sum();
        $judulPertama = $bukus->get($items->keys()->first())->judul;
        $message = $totalBuku === 1
            ? 'Peminjaman buku "'.$judulPertama.'" berhasil dicatat!'
            : 'Peminjaman '.$totalBuku.' buku berhasil dicatat!';

        return redirect()->route('pinjam.index')
            ->with('success', $message);
    }

    public function show($id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        $group = Peminjaman::with(['anggota', 'buku'])
            ->where('anggota_id', $p->anggota_id)
            ->whereDate('tgl_pinjam', $p->tgl_pinjam)
            ->whereDate('tgl_harus_kembali', $p->tgl_harus_kembali)
            ->where('status', $p->status)
            ->get();

        return view('pinjam.show', compact('p', 'group'));
    }

    /**
     * ✅ DIPERBAIKI: pakai redirect + flash session, bukan JSON response.
     * Ini yang menyebabkan "Gagal terhubung ke server" sebelumnya —
     * fetch() di JS tidak bisa menemukan meta csrf-token sehingga throw error.
     */
    public function kembalikan(Request $request, $id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        $transaksi = Peminjaman::with('buku')
            ->where('anggota_id', $p->anggota_id)
            ->whereDate('tgl_pinjam', $p->tgl_pinjam)
            ->whereDate('tgl_harus_kembali', $p->tgl_harus_kembali)
            ->where('status', 'dipinjam')
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('pinjam.index')
                ->with('error', 'Tidak ada buku yang dapat dikembalikan untuk transaksi ini.');
        }

        $tglKembali = Carbon::today();
        $totalDenda = 0;

        foreach ($transaksi as $item) {
            $itemDenda = $item->hitungDenda($tglKembali);
            $item->update([
                'status' => 'kembali',
                'tgl_realisasi_kembali' => $tglKembali,
                'denda' => $itemDenda,
            ]);
            $item->buku->increment('stok');
            $totalDenda += $itemDenda;
        }

        $judulBuku = $transaksi->count() === 1
            ? $transaksi->first()->buku->judul
            : $transaksi->count().' buku';

        // If the request expects JSON (AJAX/fetch), return JSON payload for frontend modal
        $contentType = strtolower((string) $request->header('content-type', ''));
        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('accept', ''), 'application/json') || str_contains($contentType, 'application/json')) {
            $response = [
                'judul_buku' => $judulBuku,
                'nama_anggota' => $p->anggota->nama_lengkap,
                'tgl_kembali' => $tglKembali->format('d M Y'),
                'denda' => $totalDenda,
                'denda_format' => 'Rp '.number_format($totalDenda, 0, ',', '.'),
                'jumlah_buku' => $transaksi->count(),
            ];

            if ($totalDenda > 0) {
                $response['hari_terlambat'] = $p->hariTerlambat();
            }

            return response()->json($response);
        }

        if ($totalDenda > 0) {
            $hariTelat = $p->hariTerlambat();
            $dendaFormat = 'Rp '.number_format($totalDenda, 0, ',', '.');
            $msg = '⚠️ '.($transaksi->count() === 1 ? 'Buku' : 'Buku-buku').' <strong>"'.$judulBuku.'"</strong> berhasil dikembalikan oleh '
                 .$p->anggota->nama_lengkap
                 .'. Terlambat '.$hariTelat.' hari — '
                 .'denda: <strong>'.$dendaFormat.'</strong>';

            return redirect()->route('pinjam.index')->with('info_denda', $msg);
        }

        return redirect()->route('pinjam.index')
            ->with('success', 'Buku "'.$judulBuku.'" berhasil dikembalikan oleh '.$p->anggota->nama_lengkap.'. Tidak ada denda.');
    }

    public function searchAnggota(Request $request)
    {
        $q = $request->get('q', '');
        $anggotas = Anggota::where('nama_lengkap', 'like', "%{$q}%")
            ->orWhere('nomor_induk', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'nama_lengkap', 'nomor_induk', 'kelas']);

        return response()->json($anggotas);
    }

    public function searchBuku(Request $request)
    {
        $q = $request->get('q', '');
        $bukus = Buku::with('kategori')
            ->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%");
            })
            ->where('stok', '>', 0)
            ->limit(10)
            ->get(['id', 'judul', 'pengarang', 'isbn', 'stok', 'kategori_id']);

        return response()->json($bukus);
    }

    public function cariBukuByKode(Request $request)
    {
        $kode = $request->input('kode');
        if (! $kode) {
            return response()->json(['message' => 'Kode buku tidak dikirim'], 422);
        }

        $buku = Buku::where('kode_buku', $kode)->first(['id', 'judul', 'pengarang', 'stok', 'isbn']);

        if (! $buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json($buku);
    }
}
