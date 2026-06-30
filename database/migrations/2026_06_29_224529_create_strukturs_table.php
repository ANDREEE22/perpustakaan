<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strukturs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('foto')->nullable();
            $table->integer('level')->default(1); // Angka lebih kecil = Posisi lebih tinggi (Contoh: 1 = Kepala Sekolah, 2 = Kepala Perpus, 3 = Staf)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strukturs');
    }
};