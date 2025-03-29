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
use Modules\Umkm\Entities\UmkmAlamat;
use Modules\Umkm\Entities\UmkmMBentuk;
use Modules\Umkm\Entities\UmkmMJenis;

class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm';

    protected $fillable = ['nama', 'instansi_id', 'umkm_M_bentuk_id', 'umkm_M_jenis_id', 'keterangan'];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(UmkmFoto::class);
    }

    public function kontak(): HasMany
    {
        return $this->hasMany(UmkmKontak::class);
    }

    public function alamat(): HasMany
    {
        return $this->hasMany(UmkmAlamat::class);
    }

    public function wargas()
    {
        return $this->belongsToMany(Warga::class, 'umkm_warga')
            ->withPivot(['created_at', 'updated_at', 'deleted_at'])
            ->withTimestamps();
    }

    public function bentukUsaha(): BelongsTo
    {
        return $this->belongsTo(UmkmBentukUsaha::class, 'umkm_M_bentuk_id');
    }

    public function jenisUsaha(): BelongsTo
    {
        return $this->belongsTo(UmkmJenisUsaha::class, 'umkm_M_jenis_id');
    }

    public function produks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'umkm_id');
    }
}
