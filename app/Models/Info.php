<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    protected $table = 'infos';

    protected $fillable = [
        'judul_pengumuman',
        'isi_informasi',
        'tipe_warna',
        'tanggal_publish',
    ];

    // Opsional: Untuk memastikan tanggal terformat dengan benar sebagai instance Carbon
    protected $casts = [
        'tanggal_publish' => 'date',
    ];
}