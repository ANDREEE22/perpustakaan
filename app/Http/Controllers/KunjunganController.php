<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Anggota;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Halaman utama buku tamu — form check-in + daftar hari ini.
     */
    public function index(Request $request)
    {
        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)
            : Carbon::today();

        // Daftar kunjungan di tanggal yang dipilih
        $kunjungan = Kunjungan::with('anggota')
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        // Statistik
        $stats = [
            'hari_ini'  => Kunjungan::whereDate('tanggal', Carbon::today())->count(),
            'bulan_ini' => Kunjungan::whereMonth('tanggal', Carbon::today()->month)
                                    ->whereYear('tanggal', Carbon::today()->year)
                                    ->count(),
            'total'     => Kunjungan::count(),
        ];

        return view('kunjungan.index', compact('kunjungan', 'tanggal', 'stats'));
    }

    /**
     * AJAX: cari anggota by NISN atau nama saat mengetik.
     */
    public function cariAnggota(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $anggotas = Anggota::where('nomor_induk', 'like', "%{$q}%")
            ->orWhere('nama_lengkap', 'like', "%{$q}%")
            ->limit(8)
            ->get(['id', 'nomor_induk', 'nama_lengkap', 'kelas', 'foto']);

        return response()->json($anggotas);
    }

    /**
     * AJAX: check-in anggota ke buku tamu.
     * Cegah duplikat: anggota yang sama tidak bisa check-in dua kali di hari yang sama.
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'keperluan'  => 'nullable|string|max:100',
        ]);

        $anggota   = Anggota::findOrFail($request->anggota_id);
        $today     = Carbon::today();

        // Cek sudah pernah check-in hari ini
        $sudahMasuk = Kunjungan::where('anggota_id', $anggota->id)
                                ->whereDate('tanggal', $today)
                                ->exists();

        if ($sudahMasuk) {
            return response()->json([
                'error' => 'duplicate',
                'message' => $anggota->nama_lengkap . ' sudah tercatat berkunjung hari ini.',
            ], 422);
        }

        $kunjungan = Kunjungan::create([
            'anggota_id' => $anggota->id,
            'tanggal'    => $today,
            'jam_masuk'  => Carbon::now()->format('H:i:s'),
            'keperluan'  => $request->keperluan ?: 'Umum',
        ]);

        return response()->json([
            'success'      => true,
            'id'           => $kunjungan->id,
            'nama'         => $anggota->nama_lengkap,
            'nomor_induk'  => $anggota->nomor_induk,
            'kelas'        => $anggota->kelas ?? 'Guru/Staf',
            'foto'         => $anggota->foto ? asset('storage/' . $anggota->foto) : null,
            'jam_masuk'    => Carbon::now()->format('H:i'),
            'keperluan'    => $kunjungan->keperluan,
            'jumlah_hari_ini' => Kunjungan::whereDate('tanggal', $today)->count(),
        ]);
    }

    /**
     * Hapus satu entri kunjungan.
     */
    public function destroy($id)
    {
        Kunjungan::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data kunjungan berhasil dihapus.');
    }
}