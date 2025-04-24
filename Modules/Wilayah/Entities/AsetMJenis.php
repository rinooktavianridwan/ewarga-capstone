<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetMJenis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_m_jenis';

    protected $fillable = [
        'nama'
    ];

    public function asetJenis()
    {
        return $this->hasMany(Aset::class, 'aset_m_jenis_id');
    }
}
