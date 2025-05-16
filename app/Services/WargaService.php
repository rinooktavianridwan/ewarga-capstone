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

    public function registerWarga(array $data)
    {
        return Warga::create([
            'instansi_id' => $data['instansi_id'] ?? null,
            'nama' => $data['nama'],
            'nomor_induk' => $data['nomor_induk'] ?? null,
            'nik' => $data['nik'] ?? null,
            'no_kk' => $data['no_kk'] ?? null,
            'no_tlp' => $data['no_tlp'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tgl_lahir' => $data['tgl_lahir'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }
}
