<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetFoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetFotoService
{
    public function getAll()
    {
        return AsetFoto::with('aset')->get();
    }

    public function getById(int $id): AsetFoto
    {
        $foto = AsetFoto::with('aset')->find($id);

        if (!$foto) {
            throw new ModelNotFoundException("Aset Foto dengan ID {$id} tidak ditemukan.");
        }

        return $foto;
    }

    public function create(array $data): AsetFoto
    {
        return DB::transaction(function () use ($data) {
            return AsetFoto::create($data);
        });
    }

    public function update(AsetFoto $foto, array $data): AsetFoto
    {
        DB::transaction(function () use ($foto, $data) {
            $foto->update($data);
        });

        return $foto;
    }

    public function delete(AsetFoto $foto): bool|null
    {
        return $foto->delete();
    }
}
