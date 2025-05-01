<?php

namespace Modules\UMKM\Entities;

use App\Models\Instansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Warga;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Umkm\Entities\ProdukFoto;
use Modules\Umkm\Entities\UmkmProdukFoto;

class UmkmProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_produk';

    protected $fillable = [
        'umkm_id',
        'warga_id',
        'instansi_id',
        'nama',
        'keterangan',
        'harga',
    ];

    public function Umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function UmkmProdukFoto(): HasMany
    {
        return $this->hasMany(UmkmProdukFoto::class, 'umkm_produk_id');
    }

    public function Instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}
