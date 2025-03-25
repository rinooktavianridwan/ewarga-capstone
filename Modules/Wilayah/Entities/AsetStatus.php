<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_status';

    protected $fillable = [
        'aset_penghuni_id',
        'aset_status_penghuni_id'
    ];

    public function asetPenghuni()
    {
        return $this->belongsTo(AsetPenghuni::class, 'aset_penghuni_id');
    }

    public function asetStatusPenghuni()
    {
        return $this->belongsTo(AsetStatusPenghuni::class, 'aset_status_penghuni_id');
    }
}
