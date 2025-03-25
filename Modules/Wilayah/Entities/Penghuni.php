<?php

namespace Modules\Wilayah\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warga;
use App\Models\Instansi;

class Penghuni extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'penghuni';

    protected $fillable = [
        'warga_id',
        'instansi_id'
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}
