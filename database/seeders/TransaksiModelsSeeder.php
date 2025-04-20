<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mulai = Carbon::now()->toDateString();
        $selesai = Carbon::now()->addDays(3)->toDateString();
        $durasi = Carbon::parse($mulai)->diffInDays($selesai);

        DB::table('transaksi_models')->insert([
            [
                'nama_penyewa' => 'Ahmad Setiawan',
                'jenis_identitas' => 'KTP',
                'nomor_identitas' => '1234567890123456',
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $selesai,
                'durasi_hari' => $durasi,
                'total_harga' => 180000.00,
                'status' => 'disetujui',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}