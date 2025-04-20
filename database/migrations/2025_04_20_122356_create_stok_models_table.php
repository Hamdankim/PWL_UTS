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
        Schema::create('stok_models', function (Blueprint $table) {
            $table->id('stok_id');
            $table->unsignedBigInteger('alat_id')->index;
            $table->integer('jumlah_stok')->default(0);
            $table->integer('jumlah_disewa')->default(0);
            $table->timestamps();

            $table->foreign('alat_id')->references('alat_id')->on('alat_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_models');
    }
};
