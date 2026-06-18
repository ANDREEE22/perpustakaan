<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->string('kode_buku', 50)->nullable();
            $table->string('qr_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qrs');
    }
};
