<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmkmMKontak extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    protected function umkmMKontak(): HasMany
    {
        return $this->hasMany(UmkmKontak::class, 'umkm_m_kontak_id');
    }
}
