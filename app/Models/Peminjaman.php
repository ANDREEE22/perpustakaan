<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_harus_kembali',
        'tgl_realisasi_kembali',
        'status',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tgl_pinjam'             => 'date',
        'tgl_harus_kembali'      => 'date',
        'tgl_realisasi_kembali'  => 'date',
    ];

    // ── Relasi ─────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // ── Helper ─────────────────────────────────────

    /**
     * Hitung denda berdasarkan tanggal pengembalian.
     * Rp 500 per hari keterlambatan.
     */
    public function hitungDenda(?Carbon $tglKembali = null): int
    {
        $tglKembali = $tglKembali ?? Carbon::today();

        if ($tglKembali->lte($this->tgl_harus_kembali)) {
            return 0;
        }

        $selisihHari = $this->tgl_harus_kembali->diffInDays($tglKembali);
        return $selisihHari * 500;
    }

    /**
     * Apakah peminjaman ini terlambat?
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'kembali') {
            return $this->tgl_realisasi_kembali?->gt($this->tgl_harus_kembali) ?? false;
        }
        return Carbon::today()->gt($this->tgl_harus_kembali);
    }

    /**
     * Hitung berapa hari keterlambatan saat ini.
     */
    public function hariTerlambat(): int
    {
        $batas = $this->tgl_harus_kembali;
        $acuan = $this->status === 'kembali'
            ? $this->tgl_realisasi_kembali
            : Carbon::today();

        if (!$acuan || $acuan->lte($batas)) return 0;

        return (int) $batas->diffInDays($acuan);
    }
}