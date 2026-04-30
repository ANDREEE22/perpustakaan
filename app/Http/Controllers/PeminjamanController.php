<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Daftar semua peminjaman dengan filter status & search.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku'])
                           ->orderByRaw("FIELD(status, 'dipinjam', 'kembali')")
                           ->orderBy('tgl_harus_kembali', 'asc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter keterlambatan
        if ($request->filled('filter') && $request->filter === 'terlambat') {
            $query->where('status', 'dipinjam')
                  ->where('tgl_harus_kembali', '<', Carbon::today());
        }

        // Search anggota / buku
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('anggota', fn($a) => $a->where('nama_lengkap', 'like', "%{$search}%")
                                                     ->orWhere('nomor_induk', 'like', "%{$search}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$search}%"));
            });
        }

        $peminjaman = $query->paginate(15)->withQueryString();

        // Statistik ringkas
        $stats = [
            'total'    => Peminjaman::count(),
            'aktif'    => Peminjaman::where('status', 'dipinjam')->count(),
            'kembali'  => Peminjaman::where('status', 'kembali')->count(),
            'terlambat'=> Peminjaman::where('status', 'dipinjam')
                                    ->where('tgl_harus_kembali', '<', Carbon::today())
                                    ->count(),
        ];

        return view('pinjam.index', compact('peminjaman', 'stats'));
    }

    /**
     * Form pinjam buku baru.
     */
    public function create()
    {
        // Data anggota & buku di-inject langsung di blade via {!! json_encode() !!}
        // sehingga tidak butuh fetch/API — tidak ada masalah middleware/auth
        return view('pinjam.create');
    }

    /**
     * Simpan data pinjaman baru + kurangi stok buku.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'       => 'required|exists:anggotas,id',
            'buku_id'          => 'required|exists:bukus,id',
            'tgl_pinjam'       => 'required|date',
            'tgl_harus_kembali'=> 'required|date|after_or_equal:tgl_pinjam',
            'catatan'          => 'nullable|string|max:255',
        ]);

        // Cek stok buku
        $buku = Buku::findOrFail($validated['buku_id']);
        if ($buku->stok <= 0) {
            return back()->withErrors(['buku_id' => 'Stok buku "' . $buku->judul . '" sudah habis.'])->withInput();
        }

        // Cek anggota tidak sedang meminjam buku yang sama
        $sudahPinjam = Peminjaman::where('anggota_id', $validated['anggota_id'])
                                  ->where('buku_id', $validated['buku_id'])
                                  ->where('status', 'dipinjam')
                                  ->exists();
        if ($sudahPinjam) {
            return back()->withErrors(['buku_id' => 'Anggota ini masih meminjam buku yang sama.'])->withInput();
        }

        // Simpan peminjaman
        Peminjaman::create([...$validated, 'status' => 'dipinjam', 'denda' => 0]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('pinjam.index')
                         ->with('success', 'Peminjaman buku "' . $buku->judul . '" berhasil dicatat!');
    }

    /**
     * Detail satu peminjaman.
     */
    public function show($id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);
        return view('pinjam.show', compact('p'));
    }

    /**
     * Proses pengembalian buku + hitung denda.
     * AJAX → return JSON.
     */
    public function kembalikan(Request $request, $id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        if ($p->status === 'kembali') {
            return response()->json(['error' => 'Buku ini sudah dikembalikan.'], 422);
        }

        $tglKembali = Carbon::today();
        $denda      = $p->hitungDenda($tglKembali);

        $p->update([
            'status'                 => 'kembali',
            'tgl_realisasi_kembali'  => $tglKembali,
            'denda'                  => $denda,
        ]);

        // Kembalikan stok buku
        $p->buku->increment('stok');

        return response()->json([
            'success'       => true,
            'nama_anggota'  => $p->anggota->nama_lengkap,
            'judul_buku'    => $p->buku->judul,
            'tgl_kembali'   => $tglKembali->format('d/m/Y'),
            'denda'         => $denda,
            'denda_format'  => 'Rp ' . number_format($denda, 0, ',', '.'),
            'terlambat'     => $p->hariTerlambat(),
        ]);
    }

    /**
     * API: Search anggota untuk dropdown.
     */
    public function searchAnggota(Request $request)
    {
        $q = $request->get('q', '');
        $anggotas = Anggota::where('nama_lengkap', 'like', "%{$q}%")
                            ->orWhere('nomor_induk', 'like', "%{$q}%")
                            ->limit(10)
                            ->get(['id', 'nama_lengkap', 'nomor_induk', 'kelas']);

        return response()->json($anggotas);
    }

    /**
     * API: Search buku untuk dropdown (hanya yang stok > 0).
     */
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
}