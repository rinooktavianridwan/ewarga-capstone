<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\Softdelete\SoftDeletesBoolean;
use Modules\Umkm\Entities\Umkm;
use Modules\UMKM\Entities\UmkmProduk;
use Modules\Wilayah\Entities\Aset;

/**
 * App\Models\Instansi
 *
 * @property      int                           $id
 * @property      string                        $nama
 * @property      null|int                      $provinsi_id
 * @property      null|int                      $kota_id
 * @property      null|int                      $kecamatan_id
 * @property      null|int                      $kelurahan_id
 * @property      null|Carbon                   $updated_at
 * @property      null|Carbon                   $created_at
 *
 * @property-read MKecamatan                    $kecamatan
 * @property-read MKelurahan                    $kelurahan
 * @property-read MKota                         $kota
 * @property-read MProvinsi                     $provinsi
 * @property-read int                           $creator_id
 * @property-read Collection|Laporan[]          $laporans
 * @property-read Collection|UserInstansi[]     $userInstansis
 */
class Instansi extends Model
{
    use HasFactory, SoftDeletesBoolean;

    protected $table   = 'instansi';
    protected $guarded = [];
    protected $appends = ['jumlah_warga'];

    public function mProvinsi(): BelongsTo
    {
        return $this->belongsTo(MProvinsi::class, 'provinsi_id', 'id');
    }

    public function mKota(): BelongsTo
    {
        return $this->belongsTo(MKota::class, 'kota_id', 'id');
    }

    public function mKecamatan(): BelongsTo
    {
        return $this->belongsTo(MKecamatan::class, 'kecamatan_id', 'id');
    }

    public function mKelurahan(): BelongsTo
    {
        return $this->belongsTo(MKelurahan::class, 'kelurahan_id', 'id');
    }

    public function wargaInstansi(): hasMany
    {
        return $this->hasMany(WargaInstansi::class, 'instansi_id');
    }

    public function getjumlahWargaAttribute()
    {
        return $this->wargaInstansi()->count();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class, 'instansi_id');
    }

    public function umkmProduks(): HasMany
    {
        return $this->hasMany(UmkmProduk::class, 'instansi_id');
    }

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'instansi_id');
    }
}
