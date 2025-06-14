<?php

namespace Modules\Umkm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmWarga extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'umkm_warga';

    protected $fillable = ['umkm_id', 'warga_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}
