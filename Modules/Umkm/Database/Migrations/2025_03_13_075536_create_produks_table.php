<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel UMKM Produk
        Schema::create('umkm_produk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->foreignId('instansi_id')->constrained('instansi')->cascadeOnDelete();

            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->integer('harga');

            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Foto Produk
        Schema::create('umkm_produk_foto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('umkm_produk_id')->constrained('umkm_produk')->cascadeOnDelete();
            $table->string('nama'); // nama file foto

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_produk_foto');
        Schema::dropIfExists('umkm_produk');
    }
};
