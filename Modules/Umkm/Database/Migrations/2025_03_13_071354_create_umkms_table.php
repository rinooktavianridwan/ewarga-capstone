<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('umkm_m_bentuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_m_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_m_kontak', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi')->onDelete('cascade');
            $table->foreignId('umkm_m_bentuk_id')->constrained('umkm_m_bentuk')->onDelete('cascade');
            $table->foreignId('umkm_m_jenis_id')->constrained('umkm_m_jenis')->onDelete('cascade');
            $table->string('nama');
            $table->text('alamat');
            $table->point('lokasi');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_kontak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->onDelete('cascade');
            $table->foreignId('umkm_m_kontak_id')->constrained('umkm_m_kontak')->onDelete('cascade');
            $table->string('kontak', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('file_path', 255);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->onDelete('cascade');
            $table->foreignId('umkm_id')->constrained('umkm')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['warga_id', 'umkm_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('umkm_m_bentuk');
        Schema::dropIfExists('umkm_m_jenis');
        Schema::dropIfExists('umkm_m_kontak');
        Schema::dropIfExists('umkm');
        Schema::dropIfExists('umkm_kontak');
        Schema::dropIfExists('umkm_foto');
        Schema::dropIfExists('umkm_warga');
    }
};

