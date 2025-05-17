<?php

namespace App\Services;

use App\Models\Warga;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
            'nik' => $data['nik'] ?? null,
            'no_kk' => $data['no_kk'] ?? null,
            'no_tlp' => $data['no_tlp'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tgl_lahir' => $data['tgl_lahir'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'email' => auth()->user()->email,
            'user_id' => $data['user_id'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    public function updateWarga($id, array $data, ?UploadedFile $foto = null)
    {
        $warga = Warga::findOrFail($id);

        if ($foto) {
            $filename = $warga->id . '_' . uniqid() . '_' . $foto->getClientOriginalName();
            $path = $foto->storeAs("warga_foto/{$warga->instansi_id}", $filename, 'public');

            if ($warga->foto_path && Storage::disk('public')->exists($warga->foto_path)) {
                Storage::disk('public')->delete($warga->foto_path);
            }

            $data['foto_name'] = $foto->getClientOriginalName();
            $data['foto_path'] = $path;
        }

        $warga->update($data);

        return $warga->fresh(['user', 'instansi']);
    }
}
