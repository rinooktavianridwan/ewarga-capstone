<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\AsetMJenis;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Wilayah\Entities\AsetMStatus;

class AsetMasterService
{
    public function getAll(string $type)
    {
        return match ($type) {
            'jenis' => AsetMJenis::with('asetJenis')->get(),
            'status' => AsetMStatus::with('asetStatus')->get(),
            default => throw new \Exception("Unknown master type: $type")
        };
    }

    public function getById(string $type, int $id)
    {
        $model = match ($type) {
            'jenis' => AsetMJenis::with('asetJenis')->find($id),
            'status' => AsetMStatus::with('asetStatus')->find($id),
            default => null
        };

        if (!$model) {
            throw new ModelNotFoundException("Data not found for $type with ID $id");
        }

        return $model;
    }

    public function create(string $type, array $data)
    {
        return DB::transaction(function () use ($type, $data) {
            return match ($type) {
                'jenis' => AsetMJenis::create($data),
                'status' => AsetMStatus::create($data),
                default => throw new \Exception("Unknown master type: $type")
            };
        });
    }

    public function update(string $type, $id, array $data)
    {
        return DB::transaction(function () use ($type, $id, $data) {
            $model = $this->getById($type, $id);
            $model->update($data);
            return $model;
        });
    }

    public function delete(string $type, $id)
    {
        $model = $this->getById($type, $id);
        return $model->delete();
    }
}
