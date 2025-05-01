<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmAlamat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm_alamat';

    protected $fillable = ['umkm_id', 'alamat'];

    public function Umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
