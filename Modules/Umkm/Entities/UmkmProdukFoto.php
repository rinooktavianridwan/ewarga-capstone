<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmProdukFoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_produk_foto';

    protected $fillable = ['umkm_produk_id', 'nama'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(UmkmProduk::class, 'umkm_produk_id');
    }
}
