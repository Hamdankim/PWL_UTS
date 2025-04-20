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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id')->index;
            $table->unsignedBigInteger('alat_id')->index;
            $table->integer('jumlah');
            $table->decimal('harga_sewa_saat_ini', 12, 2);
            $table->decimal('subtotal', 12, 2); // harga * jumlah * durasi
            $table->timestamps();

            $table->foreign('transaksi_id')->references('transaksi_id')->on('transaksi_models')->onDelete('cascade');
            $table->foreign('alat_id')->references('alat_id')->on('alat_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
