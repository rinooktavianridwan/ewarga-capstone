<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetMStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_m_status';

    protected $fillable = [
        'nama'
    ];

    public function asetStatus()
    {
        return $this->hasMany(AsetPenghuni::class, 'aset_m_status_id');
    }
}
