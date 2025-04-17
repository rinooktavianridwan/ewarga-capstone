<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetPenghuni;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetPenghuniService
{
    public function getAll()
    {
        return AsetPenghuni::with(['aset', 'warga', 'status'])->get();
    }

    public function getById(int $id): AsetPenghuni
    {
        $penghuni = AsetPenghuni::with(['aset', 'warga', 'status'])->find($id);

        if (!$penghuni) {
            throw new ModelNotFoundException("Aset Penghuni dengan ID {$id} tidak ditemukan.");
        }

        return $penghuni;
    }

    public function create(array $data): AsetPenghuni
    {
        return DB::transaction(function () use ($data) {
            return AsetPenghuni::create($data);
        });
    }

    public function update(AsetPenghuni $penghuni, array $data): AsetPenghuni
    {
        DB::transaction(function () use ($penghuni, $data) {
            $penghuni->update($data);
        });

        return $penghuni;
    }

    public function delete(AsetPenghuni $penghuni): bool|null
    {
        return $penghuni->delete();
    }
}
