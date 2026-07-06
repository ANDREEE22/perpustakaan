<?php

namespace App\Http\Controllers;

use App\Imports\AnggotaImport;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    /**
     * Daftar anggota dengan search & filter.
     */
    public function index(Request $request)
    {
        $query = Anggota::query();

        // Cari berdasarkan nama, nomor induk, atau kelas
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_induk', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
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
     * Hapus seluruh anggota pada satu kelas dan promosikan kelas di bawahnya.
     */
    public function hapusKelas(Request $request)
    {
        $validated = $request->validate([
            'kelas_tahun' => 'required|in:7,8,9',
        ]);

        $selectedGrade = (int) $validated['kelas_tahun'];

        $selectedPatterns = $this->kelasPatterns($selectedGrade);

        Anggota::where(function ($query) use ($selectedPatterns) {
            foreach ($selectedPatterns as $pattern) {
                $query->orWhere('kelas', 'like', $pattern);
            }
        })->delete();

        for ($grade = $selectedGrade - 1; $grade >= 7; $grade--) {
            $nextGrade = $grade + 1;
            $patterns = $this->kelasPatterns($grade);

            $anggotas = Anggota::where(function ($query) use ($patterns) {
                foreach ($patterns as $pattern) {
                    $query->orWhere('kelas', 'like', $pattern);
                }
            })->get();

            foreach ($anggotas as $anggota) {
                $updated = $this->promoteKelas($anggota->kelas);

                if ($updated !== null) {
                    $anggota->kelas = $updated;
                    $anggota->save();
                }
            }
        }

        $message = "Semua anggota kelas {$selectedGrade} berhasil dihapus.";

        if ($selectedGrade > 7) {
            $message .= ' Kelas di bawahnya telah dipromosikan satu tingkat.';
        }

        return redirect()->route('anggota.index')->with('swal', [
            'title' => 'Berhasil',
            'text' => $message,
            'icon' => 'success',
            'timer' => 2400,
            'showConfirmButton' => false,
        ]);
    }

    private function kelasPatterns(int $grade): array
    {
        return match ($grade) {
            7 => ['7%', 'VII%'],
            8 => ['8%', 'VIII%'],
            9 => ['9%', 'IX%'],
            default => [],
        };
    }

    private function promoteKelas(string $kelas): ?string
    {
        if (preg_match('/^VII(.*)$/i', $kelas, $matches)) {
            return 'VIII'.$matches[1];
        }

        if (preg_match('/^7(.*)$/', $kelas, $matches)) {
            return '8'.$matches[1];
        }

        if (preg_match('/^VIII(.*)$/i', $kelas, $matches)) {
            return 'IX'.$matches[1];
        }

        if (preg_match('/^8(.*)$/', $kelas, $matches)) {
            return '9'.$matches[1];
        }

        return null;
    }

    /**
     * Form impor anggota via Excel
     */
    public function importForm()
    {
        return view('anggota.import');
    }

    /**
     * Proses impor Excel anggota
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        $import = new AnggotaImport;
        Excel::import($import, $file);

        return redirect()->route('anggota.index')->with('swal', [
            'title' => 'Impor selesai',
            'text' => "Dimasukkan: {$import->inserted}, dilewati: {$import->skipped}.",
            'icon' => 'success',
            'timer' => 2600,
            'showConfirmButton' => false,
        ]);
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
            'nomor_induk' => 'required|string|unique:anggotas,nomor_induk',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-anggota', 'public');
        }

        Anggota::create($validated);

        return redirect()->route('anggota.index')->with('swal', [
            'title' => 'Berhasil',
            'text' => 'Anggota "'.$validated['nama_lengkap'].'" berhasil ditambahkan.',
            'icon' => 'success',
            'timer' => 2200,
            'showConfirmButton' => false,
        ]);
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
            'nomor_induk' => 'required|string|unique:anggotas,nomor_induk,'.$id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-anggota', 'public');
        }

        $anggota->update($validated);

        return redirect()->route('anggota.index')->with('swal', [
            'title' => 'Berhasil',
            'text' => 'Data "'.$anggota->nama_lengkap.'" berhasil diperbarui.',
            'icon' => 'success',
            'timer' => 2200,
            'showConfirmButton' => false,
        ]);
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

        return redirect()->route('anggota.index')->with('swal', [
            'title' => 'Berhasil dihapus',
            'text' => 'Anggota "'.$nama.'" berhasil dihapus.',
            'icon' => 'success',
            'timer' => 2200,
            'showConfirmButton' => false,
        ]);
    }
}
