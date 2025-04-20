<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stok_models')->insert([
            [
                'alat_id' => 1, // Tenda Dome
                'jumlah_stok' => 10,
                'jumlah_disewa' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'alat_id' => 2, // Kompor Portable
                'jumlah_stok' => 15,
                'jumlah_disewa' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'alat_id' => 3, // Sleeping Bag
                'jumlah_stok' => 20,
                'jumlah_disewa' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
