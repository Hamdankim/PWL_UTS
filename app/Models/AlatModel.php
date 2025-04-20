<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlatModel extends Model
{
    use HasFactory;
    protected $table = 'alat_models';
    protected $primaryKey = 'alat_id';

    protected $fillable = [
        'kategori_id',
        'alat_kode',
        'alat_nama',
        'harga_sewa',
        'gambar',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
