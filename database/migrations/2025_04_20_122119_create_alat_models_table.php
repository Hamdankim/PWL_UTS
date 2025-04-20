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
        Schema::create('alat_models', function (Blueprint $table) {
            $table->id('alat_id');
            $table->unsignedBigInteger('kategori_id')->index;
            $table->string('alat_kode', 10)->unique();
            $table->string('alat_nama', 100);
            $table->integer('harga_sewa');
            $table->string('gambar', 255)->nullable();
            $table->timestamps();

            $table->foreign('kategori_id')->references('kategori_id')->on('kategori_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat_models');
    }
};
