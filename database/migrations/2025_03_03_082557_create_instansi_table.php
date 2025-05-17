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
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('instansi')->nullOnDelete();
            $table->string('nama')->nullable();
            $table->foreignId('provinsi_id')->constrained('m_provinsi')->cascadeOnDelete();
            $table->foreignId('kota_id')->constrained('m_kota')->cascadeOnDelete();
            $table->foreignId('kecamatan_id')->constrained('m_kecamatan')->cascadeOnDelete();
            $table->foreignId('kelurahan_id')->constrained('m_kelurahan')->cascadeOnDelete();
            $table->string('rw')->nullable();
            $table->string('rt')->nullable();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('alamat')->nullable();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
        });

        Schema::create('warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->nullable()->constrained('instansi')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama');
            $table->string('nik', 16)->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('no_tlp', 13)->nullable();
            $table->string('tempat_lahir', 60)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto_name')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
        });

        Schema::create('warga_instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('instansi_id')->constrained('instansi')->cascadeOnDelete();
            $table->string('alamat')->nullable();
            $table->timestamps();
            $table->unique(['warga_id', 'instansi_id']);
        });

        Schema::create('warga_pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga_instansi');
        Schema::dropIfExists('warga');
        Schema::dropIfExists('instansi');
    }
};
