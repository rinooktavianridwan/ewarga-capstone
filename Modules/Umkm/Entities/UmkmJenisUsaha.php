<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmJenisUsaha extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_M_jenis';

    protected $fillable = ['nama'];

    public function Umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'umkm_M_jenis_id');
    }
}

