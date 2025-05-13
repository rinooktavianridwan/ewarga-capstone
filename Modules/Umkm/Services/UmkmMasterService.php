<?php

namespace Modules\Umkm\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Entities\UmkmMBentuk;
use Modules\Umkm\Entities\UmkmMJenis;
use Modules\Umkm\Entities\UmkmMKontak;
use App\Services\Traits\ResponseFormatter;

class UmkmMasterService
{
    use ResponseFormatter;

    protected $models = [
        'bentuk' => UmkmMBentuk::class,
        'jenis' => UmkmMJenis::class,
        'kontak' => UmkmMKontak::class,
    ];

    protected function getRelation(string $type): ?array
    {
        $relations = [
            'bentuk' => ['umkmBentuks'],
            'jenis' => ['umkmJenis'],
            'kontak' => ['kontaks'],
        ];

        return $relations[$type] ?? null;
    }

    protected function getModel(string $type)
    {
        if (!isset($this->models[$type])) {
            throw new ModelNotFoundException("Tipe $type tidak valid");
        }
        return $this->models[$type];
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
