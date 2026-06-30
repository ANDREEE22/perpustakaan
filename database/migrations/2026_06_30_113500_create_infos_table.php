<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->string('judul_pengumuman');
            $table->text('isi_informasi');
            $table->string('tipe_warna'); // Menyimpan opsi: amber, emerald, sky
            $table->date('tanggal_publish');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infos');
    }
};