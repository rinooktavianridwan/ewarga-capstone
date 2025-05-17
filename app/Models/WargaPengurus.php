<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WargaPengurus extends Model
{
    protected $table = 'warga_pengurus';

    protected $fillable = ['warga_id']; // tambahkan ini

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}

