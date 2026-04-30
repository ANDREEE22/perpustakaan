<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     */
    public function index()
    {
        $kategoris = Kategori::withCount('bukus')
            ->orderBy('nama')
            ->paginate(10);

        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Menampilkan form tambah kategori baru.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Kategori dengan nama ini sudah ada.',
            'nama.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori "' . $validated['nama'] . '" berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit($id)
    {
        $kategori = Kategori::withCount('bukus')->findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Mengupdate data kategori.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama,' . $id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Kategori dengan nama ini sudah ada.',
            'nama.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori "' . $kategori->nama . '" berhasil diperbarui!');
    }

    /**
     * Menghapus kategori (hanya jika tidak ada buku terkait).
     */
    public function destroy($id)
    {
        $kategori = Kategori::withCount('bukus')->findOrFail($id);

        if ($kategori->bukus_count > 0) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama . '" tidak dapat dihapus karena masih memiliki ' . $kategori->bukus_count . ' buku terkait.');
        }

        $namaKategori = $kategori->nama;
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori "' . $namaKategori . '" berhasil dihapus.');
    }
}