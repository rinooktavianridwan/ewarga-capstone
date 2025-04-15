<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Instansi;

class Aset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset';

    protected $fillable = [
        'nama',
        'alamat',
        'pemilik',
        'instansi_id',
        'aset_m_jenis_id',
        'aset_m_status_id',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function jenisAset()
    {
        return $this->belongsTo(AsetMJenis::class, 'aset_m_jenis_id');
    }
    
    public function statusAset()
    {
        return $this->belongsTo(AsetMStatus::class, 'aset_m_status_id');
    }

    public function fotos()
    {
        return $this->hasMany(AsetFoto::class, 'aset_id');
    }
}
