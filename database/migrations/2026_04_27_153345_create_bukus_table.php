<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('isbn', 50)->unique();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->string('pengarang');
            $table->string('penerbit')->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->integer('stok')->default(0);
            $table->string('lokasi_rak')->nullable();
            $table->text('description')->nullable();
            $table->string('sampul')->nullable(); // ← TAMBAHAN: path foto sampul
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};