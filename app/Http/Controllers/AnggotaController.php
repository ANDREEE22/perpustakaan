<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    /**
     * Daftar anggota dengan search & filter.
     */
    public function index(Request $request)
    {
        $query = Anggota::query();

        // Cari berdasarkan nama atau nomor induk
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tipe: siswa / guru
        if ($request->filled('tipe')) {
            if ($request->tipe === 'siswa') {
                $query->whereNotNull('kelas')->where('kelas', '!=', '');
            } elseif ($request->tipe === 'guru') {
                $query->where(function ($q) {
                    $q->whereNull('kelas')->orWhere('kelas', '');
                });
            }
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $anggotas = $query->latest()->paginate(10)->withQueryString();

        return view('anggota.index', compact('anggotas'));
    }

    /**
     * Form tambah anggota.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Simpan anggota baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_induk'   => 'required|string|unique:anggotas,nomor_induk',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas'         => 'nullable|string|max:50',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('anggota.index')
                         ->with('success', 'Anggota "' . $validated['nama_lengkap'] . '" berhasil ditambahkan!');
    }

    /**
     * Detail anggota.
     */
    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.show', compact('anggota'));
    }

    /**
     * Form edit anggota.
     */
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update data anggota.
     */
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $validated = $request->validate([
            'nomor_induk'   => 'required|string|unique:anggotas,nomor_induk,' . $id,
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas'         => 'nullable|string|max:50',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('anggota.index')
                         ->with('success', 'Data "' . $anggota->nama_lengkap . '" berhasil diperbarui!');
    }

    /**
     * Hapus anggota beserta fotonya.
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }

        $nama = $anggota->nama_lengkap;
        $anggota->delete();

        return redirect()->route('anggota.index')
                         ->with('success', 'Anggota "' . $nama . '" berhasil dihapus.');
    }
}