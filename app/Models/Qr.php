<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $table = 'qrs';

    protected $fillable = [
        'buku_id',
        'kode_buku',
        'qr_path',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
