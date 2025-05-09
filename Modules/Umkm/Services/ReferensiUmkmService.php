<?php

namespace Modules\Umkm\Services;

use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\UmkmMBentuk;
use Modules\Umkm\Entities\UmkmMJenis;
use Modules\Umkm\Entities\UmkmMKontak;
use App\Services\Traits\ResponseFormatter;

class ReferensiUmkmService
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
            'bentuk' => ['umkmBentuk'],
            'jenis' => ['umkmJenis'],
            'kontak' => ['umkmMKontak'],
        ];

        return $relations[$type] ?? null;
    }

    protected function getModel(string $type)
    {
        if (!isset($this->models[$type])) {
            throw new \Exception("Tipe master tidak dikenal: $type");
        }
        return $this->models[$type];
    }

    public function getAll(string $type)
    {
        $model = $this->getModel($type);
        return $model::all(['id', 'nama']);
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
        return $model::findOrFail($id);
    }

    public function create(string $type, array $data)
    {
        $model = $this->getModel($type);
        return DB::transaction(function () use ($model, $data) {
            return $model::create($data);
        });
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
