<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asumsi: transaksi_id = 1, alat_id = 1~3 sudah ada
        $durasi = 3;

        DB::table('detail_transaksis')->insert([
            [
                'transaksi_id' => 1,
                'alat_id' => 1, // Tenda Dome
                'jumlah' => 1,
                'harga_sewa_saat_ini' => 60000,
                'subtotal' => 1 * 60000 * $durasi,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaksi_id' => 1,
                'alat_id' => 2, // Kompor Portable
                'jumlah' => 2,
                'harga_sewa_saat_ini' => 30000,
                'subtotal' => 2 * 30000 * $durasi,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaksi_id' => 1,
                'alat_id' => 3, // Sleeping Bag
                'jumlah' => 1,
                'harga_sewa_saat_ini' => 25000,
                'subtotal' => 1 * 25000 * $durasi,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
