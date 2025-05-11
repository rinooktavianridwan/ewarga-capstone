<?php

namespace Modules\UMKM\Entities;

use App\Models\Instansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Umkm\Entities\UmkmProdukFoto;

class UmkmProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_produk';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'umkm_id',
        'instansi_id',
        'nama',
        'keterangan',
        'harga',
    ];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(UmkmProdukFoto::class, 'umkm_produk_id');
    }
}
