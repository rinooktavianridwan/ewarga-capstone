<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetFoto;
use Modules\Wilayah\Entities\Aset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetFotoService
{
    public function getAll()
    {
        return AsetFoto::with('aset')->get();
    }

    public function getAllByAset(Aset $aset)
    {
        return $aset->fotos()->get();
    }

    public function getById(int $id): AsetFoto
    {
        $foto = AsetFoto::with('aset')->find($id);

        if (!$foto) {
            throw new ModelNotFoundException("Aset Foto dengan ID {$id} tidak ditemukan.");
        }

        return $foto;
    }

    public function store(Aset $aset, array $fotoFiles, int $instansiId, int $wargaId)
    {
        foreach ($fotoFiles as $file) {
            if ($file instanceof UploadedFile) {
                $filename = $wargaId . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs("aset_foto/{$instansiId}", $filename, 'public');

                $aset->fotos()->create([
                    'nama' => $file->getClientOriginalName(),
                    'file_path' => "aset_foto/{$instansiId}/{$filename}",
                ]);
            }
        }
    }

    public function delete(Aset $aset, array $hapusFotoIds)
    {
        $fotos = $aset->fotos()->whereIn('id', $hapusFotoIds)->get();

        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_path)) {
                Storage::disk('public')->delete($foto->file_path);
            }

            $foto->delete();
        }
    }
}
