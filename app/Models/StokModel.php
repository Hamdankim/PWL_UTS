<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 'stok_models'; // Sesuai dengan tabel stok
    protected $primaryKey = 'stok_id'; // Primary key tabel stok

    protected $fillable = ['alat_id', 'jumlah_stok', 'jumlah_sewa'];

    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatModel::class, 'alat_id', 'alat_id');
    }
}
