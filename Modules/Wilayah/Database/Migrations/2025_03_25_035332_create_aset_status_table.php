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
        Schema::create('aset_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_penghuni_id')->constrained('aset_penghuni')->onDelete('cascade');
            $table->foreignId('aset_status_penghuni_id')->constrained('aset_status_penghuni')->onDelete('cascade');
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
        Schema::dropIfExists('aset_status');
    }
};
