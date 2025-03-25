<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetFoto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aset_foto';

    protected $fillable = [
        'nama',
        'aset_id'
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }
}
