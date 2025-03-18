<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk'; // Pastikan sesuai dengan nama tabel di database
    protected $fillable = [
        'nama',
        'keterangan',
        'harga',
        'foto',
        'umkm_id'
    ];

    // protected static function newFactory()
    // {
    //     return \Modules\Umkm\Database\Factories\ProdukFactory::new();
    // }

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
