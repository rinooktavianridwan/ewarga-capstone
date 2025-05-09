<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmMJenis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_M_jenis';

    protected $fillable = ['nama'];

    public function umkmJenis(): HasMany
    {
        return $this->hasMany(Umkm::class, 'umkm_m_jenis_id');
    }
}

