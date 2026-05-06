<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogBukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KunjunganController;


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Katalog Buku ────────────────────────────────────────────
    Route::get('/katalog-buku',              [KatalogBukuController::class, 'index'])->name('katalog');
    Route::get('/katalog-buku/tambah',       [KatalogBukuController::class, 'create'])->name('katalog.create');
    Route::post('/katalog-buku',             [KatalogBukuController::class, 'store'])->name('katalog.store');
    Route::get('/katalog-buku/{id}',         [KatalogBukuController::class, 'show'])->name('katalog.show');
    Route::get('/katalog-buku/{id}/edit',    [KatalogBukuController::class, 'edit'])->name('katalog.edit');
    Route::put('/katalog-buku/{id}',         [KatalogBukuController::class, 'update'])->name('katalog.update');
    Route::delete('/katalog-buku/{id}',      [KatalogBukuController::class, 'destroy'])->name('katalog.destroy');

    // ─── Kategori ────────────────────────────────────────────────
    Route::get('/kategori',                  [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/tambah',           [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori',                 [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit',        [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}',             [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}',          [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // ─── Anggota ─────────────────────────────────────────────────
    Route::resource('anggota', AnggotaController::class)->names([
        'index'   => 'anggota.index',
        'create'  => 'anggota.create',
        'store'   => 'anggota.store',
        'show'    => 'anggota.show',
        'edit'    => 'anggota.edit',
        'update'  => 'anggota.update',
        'destroy' => 'anggota.destroy',
    ]);

    // ─── Peminjaman ──────────────────────────────────────────────
    Route::get('/pinjam',                    [PeminjamanController::class, 'index'])->name('pinjam.index');
    Route::get('/pinjam/baru',               [PeminjamanController::class, 'create'])->name('pinjam.create');
    Route::post('/pinjam',                   [PeminjamanController::class, 'store'])->name('pinjam.store');
    Route::get('/pinjam/{id}',               [PeminjamanController::class, 'show'])->name('pinjam.show');
    Route::post('/pinjam/{id}/kembalikan',   [PeminjamanController::class, 'kembalikan'])->name('pinjam.kembalikan');

    // ─── Laporan ─────────────────────────────────────────────────
    Route::get('/laporan',                   [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf',        [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export-excel',      [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // ─── Kunjungan / Buku Tamu ───────────────────────────────────
    Route::get('/kunjungan',                 [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::delete('/kunjungan/{id}',         [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
});

// ─── API internal (AJAX — di luar 'verified' agar tidak redirect) ────────────
Route::middleware('auth')->group(function () {
    Route::get('/api/search-anggota',        [PeminjamanController::class, 'searchAnggota'])->name('api.search.anggota');
    Route::get('/api/search-buku',           [PeminjamanController::class, 'searchBuku'])->name('api.search.buku');
    Route::get('/api/cari-anggota',          [KunjunganController::class, 'cariAnggota'])->name('api.cari.anggota');
    Route::post('/api/check-in',             [KunjunganController::class, 'checkIn'])->name('api.checkin');
});

require __DIR__.'/settings.php';