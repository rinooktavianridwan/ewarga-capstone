<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Instansi;
use App\Models\Warga;

class Aset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset';

    protected $fillable = [
        'nama',
        'warga_id',
        'instansi_id',
        'aset_m_jenis_id',
        'alamat',
        'lokasi',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function jenis()
    {
        return $this->belongsTo(AsetMJenis::class, 'aset_m_jenis_id');
    }

    public function fotos()
    {
        return $this->hasMany(AsetFoto::class, 'aset_id');
    }

    public function asetPenghunis()
    {
        return $this->hasMany(AsetPenghuni::class, 'aset_id');
    }

    public function getLatitude()
    {
        return $this->lokasi ? (float) DB::selectOne("SELECT ST_Y('{$this->lokasi}') as latitude")->latitude : null;
    }

    public function getLongitude()
    {
        return $this->lokasi ? (float) DB::selectOne("SELECT ST_X('{$this->lokasi}') as longitude")->longitude : null;
    }
}