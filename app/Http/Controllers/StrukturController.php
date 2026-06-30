<?php

namespace App\Http\Controllers;

use App\Models\Struktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturController extends Controller
{
    // 1. Tampilan list data struktur untuk admin
    public function index()
    {
        $strukturs = Struktur::orderBy('level', 'asc')->get();
        return view('struktur.index', compact('strukturs'));
    }

    // 2. Tampilkan form tambah anggota baru
    public function create()
    {
        return view('struktur.create');
    }

    // 3. Proses simpan data anggota baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->only(['nama', 'jabatan', 'level']);

        if ($request->hasFile('foto')) {
            // Menyimpan file gambar di folder public/storage/struktur
            $path = $request->file('foto')->store('struktur', 'public');
            $data['foto'] = $path;
        }

        Struktur::create($data);

        return redirect()->route('struktur.index')->with('success', 'Anggota struktur berhasil ditambahkan!');
    }

    // 4. Tampilkan form edit data berdasarkan ID
    public function edit($id)
    {
        $struktur = Struktur::findOrFail($id);
        return view('struktur.edit', compact('struktur'));
    }

    // 5. Proses perbarui data di database (Update)
    public function update(Request $request, $id)
    {
        $struktur = Struktur::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->only(['nama', 'jabatan', 'level']);

        if ($request->hasFile('foto')) {
            // Hapus file foto lama di storage jika ada penggantian foto baru
            if ($struktur->foto) {
                Storage::disk('public')->delete($struktur->foto);
            }
            $path = $request->file('foto')->store('struktur', 'public');
            $data['foto'] = $path;
        }

        $struktur->update($data);

        return redirect()->route('struktur.index')->with('success', 'Data struktur berhasil diperbarui!');
    }

    // 6. Proses hapus data (Delete)
    public function destroy($id)
    {
        $struktur = Struktur::findOrFail($id);
        
        // Hapus file fisik foto dari storage sebelum datanya dihapus dari DB
        if ($struktur->foto) {
            Storage::disk('public')->delete($struktur->foto);
        }
        
        $struktur->delete();

        return redirect()->route('struktur.index')->with('success', 'Anggota struktur berhasil dihapus!');
    }
}