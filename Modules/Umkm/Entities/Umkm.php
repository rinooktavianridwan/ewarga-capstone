<?php

namespace Modules\Umkm\Entities;

use App\Models\Instansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmFoto;
use Modules\Umkm\Entities\UmkmKontak;


class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm';

    protected $fillable = ['nama', 'instansi_id', 'umkm_m_bentuk_id', 'umkm_m_jenis_id', 'keterangan', 'alamat', 'lokasi'];
    protected $hidden = ['lokasi', 'created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['lokasi_point'];

    public function getLokasiPointAttribute()
    {
        if (!$this->attributes['lokasi']) {
            return null;
        }

        $point = DB::selectOne("SELECT ST_X(lokasi) as longitude, ST_Y(lokasi) as latitude FROM umkm WHERE id = ?", [$this->id]);

        return [
            'latitude' => $point->latitude,
            'longitude' => $point->longitude
        ];
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(UmkmMJenis::class, 'umkm_m_jenis_id');
    }

    public function bentuk(): BelongsTo
    {
        return $this->belongsTo(UmkmMBentuk::class, 'umkm_m_bentuk_id');
    }

    public function umkmWargas(): HasMany
    {
        return $this->hasMany(UmkmWarga::class, 'umkm_id');
    }

    public function produks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'umkm_id');
    }

    public function kontaks(): HasMany
    {
        return $this->hasMany(UmkmKontak::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(UmkmFoto::class);
    }
}
