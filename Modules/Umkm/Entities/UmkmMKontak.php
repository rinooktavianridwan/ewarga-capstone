<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmMKontak extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'umkm_m_kontak';

    protected $fillable = ['nama'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function kontaks(): HasMany
    {
        return $this->hasMany(UmkmKontak::class, 'umkm_m_kontak_id');
    }
}
