<?php

namespace Modules\Umkm\Services;

use Modules\Umkm\Entities\UmkmBentukUsaha;
use Modules\Umkm\Entities\UmkmJenisUsaha;

class ReferensiUmkmService
{
    public function getAllBentukUsaha()
    {
        return UmkmBentukUsaha::all(['id', 'nama']);
    }

    public function getAllJenisUsaha()
    {
        return UmkmJenisUsaha::all(['id', 'nama']);
    }
}
