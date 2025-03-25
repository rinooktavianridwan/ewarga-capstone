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
        'instansi_id'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function fotos()
    {
        return $this->hasMany(AsetFoto::class, 'aset_id');
    }

    public function jenis()
    {
        return $this->hasMany(AsetJenis::class, 'aset_id');
    }
}
