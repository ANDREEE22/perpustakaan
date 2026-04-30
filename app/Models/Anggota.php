<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggotas';
    
    protected $fillable = [
        'nomor_induk',
        'nama_lengkap',
        'jenis_kelamin',
        'kelas',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'alamat',
        'foto'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date'
    ];
}