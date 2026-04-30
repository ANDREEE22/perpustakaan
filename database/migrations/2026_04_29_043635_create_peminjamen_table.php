<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('restrict');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('restrict');
            $table->date('tgl_pinjam');
            $table->date('tgl_harus_kembali');
            $table->date('tgl_realisasi_kembali')->nullable(); // diisi saat buku dikembalikan
            $table->enum('status', ['dipinjam', 'kembali'])->default('dipinjam');
            $table->integer('denda')->default(0);             // total denda dalam rupiah
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};