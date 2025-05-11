<?php

namespace Modules\Umkm\Services;

use Illuminate\Http\UploadedFile;
use Modules\Umkm\Entities\Umkm;

class UmkmFotoService
{
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
}