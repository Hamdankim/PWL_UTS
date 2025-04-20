<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id',
        'alat_id',
        'jumlah',
        'harga_sewa_saat_ini',
        'subtotal'
    ];

    protected $casts = [
        'harga_sewa_saat_ini' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiModel::class, 'transaksi_id', 'transaksi_id');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatModel::class, 'alat_id', 'alat_id');
    }
}