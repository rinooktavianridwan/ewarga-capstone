<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\Softdelete\SoftDeletesBoolean;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;


/**
 * App\Models\Warga
 *
 * @property      int                            $id
 * @property      int                            $instansi_id
 * @property      int                            $user_id
 * @property      int                            $hubungan_keluarga_id
 * @property      string                         $nama
 * @property      string                         $nomor_induk
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


    public function wargaInstansi(): HasMany
    {
        return $this->HasMany(WargaInstansi::class, 'warga_id', 'id');
    }





}
