<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetJenis extends Model
{
    use HasFactory;

    protected $table = 'aset_jenis';

    protected $fillable = [
        'aset_id',
        'aset_daftar_jenis_id'
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function asetDaftarJenis()
    {
        return $this->belongsTo(AsetDaftarJenis::class, 'aset_daftar_jenis_id');
    }
}
