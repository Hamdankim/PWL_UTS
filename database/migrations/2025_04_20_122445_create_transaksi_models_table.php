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
        Schema::create('transaksi_models', function (Blueprint $table) {
            $table->id('transaksi_id');

            // Data penyewa
            $table->string('nama_penyewa', 100);
            $table->enum('jenis_identitas', ['KTP', 'SIM', 'Paspor'])->default('KTP');
            $table->string('nomor_identitas', 50);

            // Info sewa
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi_hari'); // bisa dihitung otomatis
            $table->decimal('total_harga', 12, 2);

            // Status transaksi
            $table->enum('status', ['pending', 'disetujui', 'dibatalkan', 'selesai'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_models');
    }
};
