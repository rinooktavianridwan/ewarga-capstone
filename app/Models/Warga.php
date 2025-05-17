<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\Softdelete\SoftDeletesBoolean;
use App\Models\WargaPengurus;
use Modules\Umkm\Entities\UmkmWarga;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Entities\AsetPenghuni;

/**
 * App\Models\Warga
 *
 * @property      int                            $id
 * @property      int                            $instansi_id
 * @property      int                            $user_id
 * @property      int                            $hubungan_keluarga_id
 * @property      string                         $nama
 * @property      string                         $nik
 * @property      null|string                    $no_kk
 * @property      null|string                    $no_tlp
 * @property      null|string                    $tempat_lahir
 * @property      null|string                    $tgl_lahir
 * @property      null|string                    $jenis_kelamin
 * @property      null|string                    $alamat
 * @property      null|Carbon                    $created_at
 * @property      null|Carbon                    $updated_at
 *

 */

class Warga extends Model
{
    use HasFactory, SoftDeletesBoolean;

    protected $table   = 'warga';
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
        return $this->hasMany(WargaInstansi::class, 'warga_id');
    }

    public function umkmWargas(): HasMany
    {
        return $this->hasMany(UmkmWarga::class, 'warga_id');
    }

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'warga_id');
    }

    public function asetPenghunis(): HasMany
    {
        return $this->hasMany(AsetPenghuni::class, 'warga_id');
    }

    public function pengurus()
    {
        return $this->hasOne(WargaPengurus::class, 'warga_id');
    }
}
