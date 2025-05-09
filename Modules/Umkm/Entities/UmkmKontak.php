<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmKontak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_kontak';

    protected $fillable = ['umkm_id', 'umkm_m_kontak_id'];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function kontak(): BelongsTo
    {
        return $this->belongsTo(UmkmMKontak::class, 'umkm_m_kontak_id');
    }
}
