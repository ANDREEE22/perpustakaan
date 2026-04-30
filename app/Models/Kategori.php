<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama'];

    /**
     * Relasi: satu kategori memiliki banyak buku.
     */
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}