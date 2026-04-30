<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogBukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur search, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter pencarian: judul, pengarang, isbn
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori_id
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan status stok
        if ($request->filled('status')) {
            match ($request->status) {
                'tersedia' => $query->where('stok', '>', 5),
                'terbatas' => $query->whereBetween('stok', [1, 5]),
                'habis'    => $query->where('stok', 0),
                default    => null,
            };
        }

        $data_buku = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('katalog_buku.index', compact('data_buku', 'kategoris'));
    }

    /**
     * Menampilkan form tambah buku baru.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('katalog_buku.create', compact('kategoris'));
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isbn'         => 'required|string|max:50|unique:bukus,isbn',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'sampul'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload sampul jika ada
        if ($request->hasFile('sampul')) {
            $validated['sampul'] = $request->file('sampul')->store('sampul_buku', 'public');
        }

        Buku::create($validated);

        return redirect()->route('katalog')->with('success', 'Buku "' . $validated['judul'] . '" berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu buku.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('katalog_buku.show', compact('buku'));
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit($id)
    {
        $buku      = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama')->get();
        return view('katalog_buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Mengupdate data buku di database.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isbn'         => 'required|string|max:50|unique:bukus,isbn,' . $id,
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'sampul'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload sampul baru & hapus yang lama jika ada
        if ($request->hasFile('sampul')) {
            if ($buku->sampul) {
                Storage::disk('public')->delete($buku->sampul);
            }
            $validated['sampul'] = $request->file('sampul')->store('sampul_buku', 'public');
        }

        $buku->update($validated);

        return redirect()->route('katalog')->with('success', 'Buku "' . $buku->judul . '" berhasil diperbarui!');
    }

    /**
     * Menghapus data buku dari database.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Hapus file sampul dari storage jika ada
        if ($buku->sampul) {
            Storage::disk('public')->delete($buku->sampul);
        }

        $judulBuku = $buku->judul;
        $buku->delete();

        return redirect()->route('katalog')->with('success', 'Buku "' . $judulBuku . '" berhasil dihapus.');
    }
}