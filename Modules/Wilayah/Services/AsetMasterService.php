<?php

namespace Modules\Wilayah\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Wilayah\Entities\AsetMJenis;
use Modules\Wilayah\Entities\AsetMStatus;

class AsetMasterService
{
    protected array $models = [
        'jenis' => AsetMJenis::class,
        'status' => AsetMStatus::class,
    ];

    protected array $relations = [
        'jenis' => 'asetJenis',
        'status' => 'asetStatus',
    ];

    protected function getModel(string $type): string
    {
        if (!isset($this->models[$type])) {
            throw new ModelNotFoundException("Tipe $type tidak valid");
        }
        return $this->models[$type];
    }

    protected function getRelation(string $type): ?string
    {
        return $this->relations[$type] ?? null;
    }

    public function getAll(string $type)
    {
        $model = $this->getModel($type);
        $relation = $this->getRelation($type);

        $data =  $relation
            ? $model::with($relation)->get()
            : $model::all();
        if ($data->isEmpty()) {
            throw new ModelNotFoundException("Data $type aset tidak ditemukan");
        }
        return $data;
    }

    public function getMultiple(array $types)
    {
        $result = [];
        foreach ($types as $type) {
            $model = $this->getModel($type);
            $relation = $this->getRelation($type);

            $data = $relation
                ? $model::with($relation)->get()
                : $model::all();

            $result[$type] = $data;
        }

        return $result;
    }

    public function getById(string $type, int $id)
    {
        $model = $this->getModel($type);
        $relation = $this->getRelation($type);

        $query = $relation ? $model::with($relation) : $model::query();
        return $query->findOrFail($id);
    }

    public function create(string $type, array $data)
    {
        $model = $this->getModel($type);
        return DB::transaction(fn() => $model::create($data));
    }

    public function update(string $type, int $id, array $data)
    {
        $model = $this->getModel($type);
        $record = $model::findOrFail($id);

        DB::transaction(fn() => $record->update($data));
        return $record;
    }

    public function delete(string $type, int $id)
    {
        $model = $this->getModel($type);
        $record = $model::findOrFail($id);

        DB::transaction(fn() => $record->delete());
        return $record;
    }
}
