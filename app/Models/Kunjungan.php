<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $fillable = [
        'anggota_id',
        'tanggal',
        'jam_masuk',
        'keperluan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}