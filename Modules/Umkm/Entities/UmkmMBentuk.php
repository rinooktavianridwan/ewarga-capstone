<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmMBentuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_m_bentuk';

    protected $fillable = ['nama'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function umkmBentuks(): HasMany
    {
        return $this->hasMany(Umkm::class, 'umkm_m_bentuk_id');
    }
}
