<?php

namespace Modules\Wilayah\Entities;

use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetPenghuni extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_penghuni';

    protected $fillable = [
        'warga_id',
        'aset_m_status_id',
        'aset_id',

    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function status()
    {
        return $this->belongsTo(AsetMStatus::class, 'aset_m_status_id');
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }
}
