<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('umkm_M_bentuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('umkm_M_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->nullable()->constrained('instansi')->nullOnDelete();
            $table->foreignId('umkm_M_bentuk_id')->nullable()->constrained('umkm_M_bentuk')->nullOnDelete();
            $table->foreignId('umkm_M_jenis_id')->nullable()->constrained('umkm_M_jenis')->nullOnDelete();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_kontak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->string('kontak', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->point('alamat');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('umkm_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->foreignId('umkm_id')->constrained('umkm')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['warga_id', 'umkm_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('umkm_warga');
        Schema::dropIfExists('umkm_foto');
        Schema::dropIfExists('umkm_alamat');
        Schema::dropIfExists('umkm_kontak');
        Schema::dropIfExists('umkm');
        Schema::dropIfExists('umkm_M_jenis');
        Schema::dropIfExists('umkm_M_bentuk');
    }

};

