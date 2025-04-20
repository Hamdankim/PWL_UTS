<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlatModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alat_models')->insert([
            [
                'kategori_id' => 1, // Tenda
                'alat_kode' => 'ALT001',
                'alat_nama' => 'Tenda Dome 4 Orang',
                'harga_sewa' => 60000,
                'gambar' => 'tenda_dome.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2, // Kompor
                'alat_kode' => 'ALT002',
                'alat_nama' => 'Kompor Portable',
                'harga_sewa' => 30000,
                'gambar' => 'kompor_portable.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3, // Sleeping Bag
                'alat_kode' => 'ALT003',
                'alat_nama' => 'Sleeping Bag Polar',
                'harga_sewa' => 25000,
                'gambar' => 'sleeping_bag.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
