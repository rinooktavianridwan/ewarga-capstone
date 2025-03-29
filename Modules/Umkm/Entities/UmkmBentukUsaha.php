<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UmkmBentukUsaha extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_M_bentuk';

    protected $fillable = ['nama'];

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class, 'umkm_M_bentuk_id');
    }
}
