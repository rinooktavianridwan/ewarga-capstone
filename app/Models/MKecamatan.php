<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKecamatan extends Model
{
    use HasFactory;

    protected $table      = 'm_kecamatan';
    public    $timestamps = false;

    public function mProvinsi()
    {
      return $this->belongsTo(MProvinsi::class, 'provinsi_id');
    }

    public function mKota()
    {
      return $this->belongsTo(MKota::class, 'kota_id');
    }
}
