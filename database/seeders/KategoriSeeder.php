<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Fiksi'],
            ['nama' => 'Non-Fiksi'],
            ['nama' => 'Buku Paket'],
            ['nama' => 'Sejarah'],
            ['nama' => 'Sains'],
            ['nama' => 'Matematika'],
            ['nama' => 'Bahasa'],
            ['nama' => 'Agama'],
            ['nama' => 'Komputer'],
            ['nama' => 'Ensiklopedia'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}