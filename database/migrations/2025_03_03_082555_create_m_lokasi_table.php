<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('m_kota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('m_provinsi')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('m_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('m_provinsi')->onDelete('cascade');
            $table->foreignId('kota_id')->constrained('m_kota')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });
        Schema::create('m_kelurahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('m_provinsi')->onDelete('cascade');
            $table->foreignId('kota_id')->constrained('m_kota')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained('m_kecamatan')->onDelete('cascade');
            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kelurahan');
        Schema::dropIfExists('m_kecamatan');
        Schema::dropIfExists('m_kota');
        Schema::dropIfExists('m_provinsi');
    }
};
