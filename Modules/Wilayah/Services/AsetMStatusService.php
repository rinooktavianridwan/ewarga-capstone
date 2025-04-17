<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetMStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetMStatusService
{
    public function getAll()
    {
        return AsetMStatus::with('asetStatus')->get();
    }

    public function getById(int $id): AsetMStatus
    {
        $status = AsetMStatus::with('asetStatus')->find($id);

        if (!$status) {
            throw new ModelNotFoundException("Status Aset dengan ID {$id} tidak ditemukan.");
        }

        return $status;
    }

    public function create(array $data): AsetMStatus
    {
        return DB::transaction(function () use ($data) {
            return AsetMStatus::create($data);
        });
    }

    public function update(AsetMStatus $status, array $data): AsetMStatus
    {
        DB::transaction(function () use ($status, $data) {
            $status->update($data);
        });

        return $status;
    }

    public function delete(AsetMStatus $status): bool|null
    {
        return $status->delete();
    }
}
