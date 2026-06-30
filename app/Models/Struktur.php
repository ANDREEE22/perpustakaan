<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Class Eloquent utama Laravel

class Struktur extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'strukturs';
    
    // Kolom database yang boleh diisi
    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'level'
    ];
}