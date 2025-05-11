<?php

namespace Modules\Umkm\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Entities\UmkmFoto;

class UmkmFotoService
{
    public function getAll()
    {
        $data = UmkmFoto::with('umkm')->get();

        if ($data->isEmpty()) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return $data;
    }

    public function getAllByAset(Umkm $umkm)
    {
        $data = $umkm->fotos()->get();

        if ($data->isEmpty()) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return $data;
    }

    public function getById(int $id): UmkmFoto
    {
        $foto = UmkmFoto::with('aset')->find($id);

        if (!$foto) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return $foto;
    }
    public function store(Umkm $umkm, array $fotoFiles, int $instansiId)
    {
        foreach ($fotoFiles as $file) {
            if ($file instanceof UploadedFile) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs("umkm_foto/{$instansiId}", $filename, 'public');

                $umkm->fotos()->create([
                    'nama' => $file->getClientOriginalName(),
                    'file_path' => "umkm_foto/{$instansiId}/{$filename}",
                ]);
            }
        }
    }

    public function delete(Umkm $umkm, array $hapusFotoIds)
    {
        $fotos = $umkm->fotos()->whereIn('id', $hapusFotoIds)->get();

        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_path)) {
                Storage::disk('public')->delete($foto->file_path);
            }

            $foto->delete();
        }
    }
}
