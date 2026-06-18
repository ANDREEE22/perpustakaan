<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // Kode buku: bisa kosong untuk data lama, tambahkan index unik jika diisi
            $table->string('kode_buku', 50)->nullable()->unique()->after('judul');
        });
    }

    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropUnique(['kode_buku']);
            $table->dropColumn('kode_buku');
        });
    }
};
