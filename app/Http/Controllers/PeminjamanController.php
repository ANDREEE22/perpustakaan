<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
                $q->whereHas('anggota', fn($a) => $a->where('nama_lengkap', 'like', "%{$search}%")
                                                     ->orWhere('nomor_induk', 'like', "%{$search}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => Peminjaman::count(),
            'aktif'     => Peminjaman::where('status', 'dipinjam')->count(),
            'kembali'   => Peminjaman::where('status', 'kembali')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                                     ->where('tgl_harus_kembali', '<', Carbon::today())
                                     ->count(),
        ];

        return view('pinjam.index', compact('peminjaman', 'stats'));
    }

    public function create()
    {
        return view('pinjam.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'        => 'required|exists:anggotas,id',
            'buku_id'           => 'required|exists:bukus,id',
            'tgl_pinjam'        => 'required|date',
            'tgl_harus_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'catatan'           => 'nullable|string|max:255',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok <= 0) {
            return back()->withErrors(['buku_id' => 'Stok buku "' . $buku->judul . '" sudah habis.'])->withInput();
        }

        $sudahPinjam = Peminjaman::where('anggota_id', $validated['anggota_id'])
                                  ->where('buku_id', $validated['buku_id'])
                                  ->where('status', 'dipinjam')
                                  ->exists();
        if ($sudahPinjam) {
            return back()->withErrors(['buku_id' => 'Anggota ini masih meminjam buku yang sama.'])->withInput();
        }

        Peminjaman::create([...$validated, 'status' => 'dipinjam', 'denda' => 0]);
        $buku->decrement('stok');

        return redirect()->route('pinjam.index')
                         ->with('success', 'Peminjaman buku "' . $buku->judul . '" berhasil dicatat!');
    }

    public function show($id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);
        return view('pinjam.show', compact('p'));
    }

    /**
     * ✅ DIPERBAIKI: pakai redirect + flash session, bukan JSON response.
     * Ini yang menyebabkan "Gagal terhubung ke server" sebelumnya —
     * fetch() di JS tidak bisa menemukan meta csrf-token sehingga throw error.
     */
    public function kembalikan(Request $request, $id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        if ($p->status === 'kembali') {
            return redirect()->route('pinjam.index')
                             ->with('error', 'Buku "' . $p->buku->judul . '" sudah dikembalikan sebelumnya.');
        }

        $tglKembali = Carbon::today();
        $denda      = $p->hitungDenda($tglKembali);

        $p->update([
            'status'                => 'kembali',
            'tgl_realisasi_kembali' => $tglKembali,
            'denda'                 => $denda,
        ]);

        // Kembalikan stok buku
        $p->buku->increment('stok');

        // Buat flash message yang informatif
        if ($denda > 0) {
            $hariTelat   = $p->hariTerlambat();
            $dendaFormat = 'Rp ' . number_format($denda, 0, ',', '.');
            $msg = '⚠️ Buku <strong>"' . $p->buku->judul . '"</strong> berhasil dikembalikan oleh '
                 . $p->anggota->nama_lengkap
                 . '. Terlambat ' . $hariTelat . ' hari — '
                 . 'denda: <strong>' . $dendaFormat . '</strong>';

            return redirect()->route('pinjam.index')->with('info_denda', $msg);
        }

        return redirect()->route('pinjam.index')
                         ->with('success', 'Buku "' . $p->buku->judul . '" berhasil dikembalikan oleh ' . $p->anggota->nama_lengkap . '. Tidak ada denda.');
    }

    public function searchAnggota(Request $request)
    {
        $q        = $request->get('q', '');
        $anggotas = Anggota::where('nama_lengkap', 'like', "%{$q}%")
                            ->orWhere('nomor_induk', 'like', "%{$q}%")
                            ->limit(10)
                            ->get(['id', 'nama_lengkap', 'nomor_induk', 'kelas']);

        return response()->json($anggotas);
    }

    public function searchBuku(Request $request)
    {
        $q     = $request->get('q', '');
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
        if (!$kode) {
            return response()->json(['message' => 'Kode buku tidak dikirim'], 422);
        }

        $buku = Buku::where('kode_buku', $kode)->first(['id', 'judul', 'pengarang', 'stok', 'isbn']);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json($buku);
    }
}