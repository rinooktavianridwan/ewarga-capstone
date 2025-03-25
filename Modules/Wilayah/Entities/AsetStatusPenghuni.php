<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetStatusPenghuni extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_status_penghuni';

    protected $fillable = [
        'nama'
    ];

    public function asetStatus()
    {
        return $this->hasMany(AsetStatus::class, 'aset_status_penghuni_id');
    }
}
