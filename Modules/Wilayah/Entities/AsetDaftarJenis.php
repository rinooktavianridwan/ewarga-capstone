<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetDaftarJenis extends Model
{
    use HasFactory;

    protected $table = 'aset_daftar_jenis';

    protected $fillable = [
        'nama'
    ];

    public function asetJenis()
    {
        return $this->hasMany(AsetJenis::class, 'aset_daftar_jenis_id');
    }
}
