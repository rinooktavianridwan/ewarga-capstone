<?php

namespace Modules\Umkm\Entities;

use App\Models\Instansi;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmFoto;
use Modules\Umkm\Entities\UmkmKontak;

class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm';

    protected $fillable = ['nama', 'instansi_id', 'umkm_m_bentuk_id', 'umkm_m_jenis_id', 'keterangan', 'alamat', 'lokasi'];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function umkmWargas(): HasMany
    {
        return $this->hasMany(UmkmWarga::class, 'umkm_id');
    }

    public function UmkmProduks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'umkm_id');
    }

    public function umkmFotos(): HasMany
    {
        return $this->hasMany(UmkmFoto::class);
    }

    public function umkmKontaks(): HasMany
    {
        return $this->hasMany(UmkmKontak::class);
    }

    public function UmkmBentukUsaha(): BelongsTo
    {
        return $this->belongsTo(UmkmMBentuk::class, 'umkm_M_bentuk_id');
    }

    public function UmkmJenisUsaha(): BelongsTo
    {
        return $this->belongsTo(UmkmMJenis::class, 'umkm_M_jenis_id');
    }

}
