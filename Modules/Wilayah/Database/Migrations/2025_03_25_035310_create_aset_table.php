<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi')->onDelete('cascade');
            $table->foreignId('aset_m_jenis_id')->constrained('aset_m_jenis')->onDelete('cascade');
            $table->foreignId('aset_m_status_id')->constrained('aset_m_status')->onDelete('cascade');
            $table->string('nama', 100);
            $table->point('alamat');
            $table->string('pemilik', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aset');
    }
};
