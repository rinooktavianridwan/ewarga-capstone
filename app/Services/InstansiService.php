<?php

namespace App\Services;

use App\Models\Instansi;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InstansiService
{
    public function getAll()
    {
        $instansi = Instansi::with(['mProvinsi', 'mKota', 'mKecamatan', 'mKelurahan'])->get();
        if ($instansi->isEmpty()) {
            throw new ModelNotFoundException("Data instansi tidak ditemukan");
        }
        return $instansi;
    }

    public function getById($id)
    {
        $instansi = Instansi::with(['mProvinsi', 'mKota', 'mKecamatan', 'mKelurahan'])->find($id);
        if (!$instansi) {
            throw new ModelNotFoundException("Data instansi tidak ditemukan");
        }
        return $instansi;
    }
}
