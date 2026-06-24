<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogBukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanKunjunganController;
use App\Http\Controllers\PeminjamanController;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $bukus = Buku::with('kategori')->get();
    $kategoris = Kategori::all();

    $bukuData = $bukus->map(function ($buku) {
        return [
            'id' => $buku->id,
            'judul' => $buku->judul,
            'penulis' => $buku->pengarang,
            'kategori' => $buku->kategori->nama ?? 'Umum',
            'tahun' => $buku->tahun_terbit,
            'penerbit' => $buku->penerbit,
            'status' => $buku->stok > 0 ? 'tersedia' : 'dipinjam',
            'kode' => $buku->kode_buku,
            'deskripsi' => $buku->description ?? 'Deskripsi tidak tersedia',
            'sampul' => $buku->sampul ? asset('storage/'.$buku->sampul) : asset('images/placeholder-book.svg'),
        ];
    })->toArray();

    // Library Statistics
    $totalBuku = Buku::count();
    $anggotaAktif = Anggota::count();
    $pengunjungBulanIni = Kunjungan::whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->count();
    $kepuasan = 98; // Default satisfaction rate

    $statistics = compact('totalBuku', 'anggotaAktif', 'pengunjungBulanIni', 'kepuasan');

    // Monthly Chart Data (Last 5 months)
    $monthlyData = [];
    $chartLabels = [];
    $chartValues = [];

    for ($i = 4; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $month = $date->month;
        $year = $date->year;

        $count = Kunjungan::whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();

        $chartLabels[] = $date->format('M');
        $chartValues[] = $count;
        $monthlyData[] = [
            'bulan' => $date->format('F'),
            'label' => $date->format('M'),
            'nilai' => $count,
        ];
    }

    return view('welcome', compact('bukus', 'kategoris', 'bukuData', 'statistics', 'monthlyData', 'chartValues', 'chartLabels'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Katalog Buku ────────────────────────────────────────────
    Route::get('/katalog-buku', [KatalogBukuController::class, 'index'])->name('katalog');
    Route::get('/katalog-buku/tambah', [KatalogBukuController::class, 'create'])->name('katalog.create');
    Route::post('/katalog-buku', [KatalogBukuController::class, 'store'])->name('katalog.store');
    Route::post('/katalog-buku/import', [KatalogBukuController::class, 'import'])->name('katalog.import');
    Route::get('/katalog-buku/{id}', [KatalogBukuController::class, 'show'])->name('katalog.show');
    Route::get('/katalog-buku/{id}/print-qr', [KatalogBukuController::class, 'printQr'])->name('katalog.printqr');
    Route::get('/katalog-buku/{id}/download-qr', [KatalogBukuController::class, 'downloadQrJpeg'])->name('katalog.downloadqr');
    Route::get('/katalog-buku/{id}/edit', [KatalogBukuController::class, 'edit'])->name('katalog.edit');
    Route::put('/katalog-buku/{id}', [KatalogBukuController::class, 'update'])->name('katalog.update');
    Route::delete('/katalog-buku/{id}', [KatalogBukuController::class, 'destroy'])->name('katalog.destroy');

    // ─── Kategori ────────────────────────────────────────────────
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/tambah', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // ─── Anggota ─────────────────────────────────────────────────
    // Anggota - import Excel (form + process) — HARUS sebelum resource routes
    Route::get('/anggota/import', [AnggotaController::class, 'importForm'])->name('anggota.import.form');
    Route::post('/anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');

    Route::resource('anggota', AnggotaController::class)->names([
        'index' => 'anggota.index',
        'create' => 'anggota.create',
        'store' => 'anggota.store',
        'show' => 'anggota.show',
        'edit' => 'anggota.edit',
        'update' => 'anggota.update',
        'destroy' => 'anggota.destroy',
    ]);

    // ─── Peminjaman ──────────────────────────────────────────────
    Route::get('/pinjam', [PeminjamanController::class, 'index'])->name('pinjam.index');
    Route::get('/pinjam/baru', [PeminjamanController::class, 'create'])->name('pinjam.create');
    Route::post('/pinjam', [PeminjamanController::class, 'store'])->name('pinjam.store');
    Route::get('/pinjam/{id}', [PeminjamanController::class, 'show'])->name('pinjam.show');
    Route::post('/pinjam/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('pinjam.kembalikan');

    // ─── Laporan ─────────────────────────────────────────────────
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // ─── Kunjungan / Buku Tamu ───────────────────────────────────
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::delete('/kunjungan/{id}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

    // ─── Laporan Kunjungan ─────────────────────────────────────
    Route::get('/laporan/kunjungan', [LaporanKunjunganController::class, 'index'])->name('laporan.kunjungan');
    Route::get('/laporan/kunjungan/export', [LaporanKunjunganController::class, 'exportExcel'])->name('laporan.kunjungan.export');
});

// ─── API internal (AJAX — di luar 'verified' agar tidak redirect) ────────────
Route::middleware('auth')->group(function () {
    Route::get('/api/search-anggota', [PeminjamanController::class, 'searchAnggota'])->name('api.search.anggota');
    Route::get('/api/search-buku', [PeminjamanController::class, 'searchBuku'])->name('api.search.buku');
    Route::post('/api/cari-buku-kode', [PeminjamanController::class, 'cariBukuByKode'])->name('api.cari.buku-kode');
    Route::get('/api/cari-anggota', [KunjunganController::class, 'cariAnggota'])->name('api.cari.anggota');
    Route::post('/api/check-in', [KunjunganController::class, 'checkIn'])->name('api.checkin');
});

require __DIR__.'/settings.php';
