<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_models')->insert([
            [
                'kategori_kode' => 'TND001',
                'kategori_nama' => 'Tenda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'KMP002',
                'kategori_nama' => 'Kompor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'SBG003',
                'kategori_nama' => 'Sleeping Bag',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
