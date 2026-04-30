<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk')->unique(); // NISN untuk siswa, NIP untuk guru
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kelas')->nullable(); // VII-A, VIII-B, IX-C, atau "Guru"
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable(); // path foto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};