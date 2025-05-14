<?php

namespace App\Services;

use App\Models\Warga;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WargaService
{
    public function getAll()
    {
        $warga = Warga::with(['user', 'instansi'])->get();
        if ($warga->isEmpty()) {
            throw new ModelNotFoundException("Data warga tidak ditemukan");
        }
        return $warga;
    }

    public function getById($id)
    {
        $warga = Warga::with(['user', 'instansi'])->find($id);
        if (!$warga) {
            throw new ModelNotFoundException("Data warga tidak ditemukan");
        }
        return $warga;
    }

    public function hasAccess($userId, $instansiId)
    {
        $hasAccess = Warga::where('user_id', $userId)
            ->where('instansi_id', $instansiId)
            ->exists();

        return $hasAccess;
    }
}
