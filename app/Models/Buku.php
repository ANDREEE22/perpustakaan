<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';
    
    // Tentukan kolom mana yang boleh diisi secara massal
    protected $fillable = [
        'kode_buku',
        'judul',
        'isbn',
        'kategori_id',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'stok',
        'lokasi_rak',
        'description',
        'sampul'
    ];
    
    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi ke QR
    public function qr()
    {
        return $this->hasOne(Qr::class, 'buku_id');
    }
}