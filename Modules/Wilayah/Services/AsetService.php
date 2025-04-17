<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\Aset;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetService
{
    public function getAll()
    {
        return Aset::with(['instansi', 'warga', 'jenisAset', 'fotos', 'asetPenghunis'])->get();
    }

    public function getById(int $id): Aset
    {
        $aset = Aset::with(['instansi', 'warga', 'jenisAset', 'fotos', 'asetPenghunis'])->find($id);

        if (!$aset) {
            throw new ModelNotFoundException("Aset dengan ID {$id} tidak ditemukan.");
        }

        return $aset;
    }

    public function create(array $data): Aset
    {
        return DB::transaction(function () use ($data) {
            return Aset::create($data);
        });
    }

    public function update(Aset $aset, array $data): Aset
    {
        DB::transaction(function () use ($aset, $data) {
            $aset->update($data);
        });

        return $aset;
    }

    public function delete(Aset $aset): bool|null
    {
        return $aset->delete();
    }
}
