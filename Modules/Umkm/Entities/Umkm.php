<?php

namespace Modules\Umkm\Entities;

use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'nama',
        'jenis_usaha',
        'kontak',
        'warga_id',
        'alamat',
        'foto',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
