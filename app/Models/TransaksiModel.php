<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiModel extends Model
{
    use HasFactory;

    protected $table = 'transaksi_models';
    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'nama_penyewa',
        'jenis_identitas',
        'nomor_identitas',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'total_harga',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_harga' => 'decimal:2'
    ];

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'transaksi_id');
    }
}
