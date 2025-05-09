<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmWarga extends Model
{
    use HasFactory;

    protected $table = 'umkm_warga';

    protected $fillable = ['umkm_id', 'warga_id'];

    protected function umkmWarga(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    protected function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}
