<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Umkm\Entities\Umkm;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';
    protected $guarded = [];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }


    public function wargaInstansi(): HasMany
    {
        return $this->hasMany(WargaInstansi::class, 'warga_id', 'id');
    }


    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'warga_id', 'id');
    }
}
