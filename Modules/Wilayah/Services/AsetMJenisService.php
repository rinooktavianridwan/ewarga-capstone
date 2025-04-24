<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetMJenis;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetMJenisService
{
    public function getAll()
    {
        return AsetMJenis::with('asetJenis')->get();
    }

    public function getById(int $id): AsetMJenis
    {
        $jenis = AsetMJenis::with('asetJenis')->find($id);

        if (!$jenis) {
            throw new ModelNotFoundException("Jenis Aset dengan ID {$id} tidak ditemukan.");
        }

        return $jenis;
    }

    public function create(array $data): AsetMJenis
    {
        return DB::transaction(function () use ($data) {
            return AsetMJenis::create($data);
        });
    }

    public function update(AsetMJenis $jenis, array $data): AsetMJenis
    {
        DB::transaction(function () use ($jenis, $data) {
            $jenis->update($data);
        });

        return $jenis;
    }

    public function delete(AsetMJenis $jenis): bool|null
    {
        return $jenis->delete();
    }
}
