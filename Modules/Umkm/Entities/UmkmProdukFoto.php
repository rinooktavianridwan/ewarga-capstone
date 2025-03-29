<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UmkmProdukFoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_produk_foto';

    protected $fillable = [
        'umkm_produk_id',
        'nama',
    ];

    public function produk()
    {
        return $this->belongsTo(UmkmProduk::class, 'umkm_produk_id');
    }
}
