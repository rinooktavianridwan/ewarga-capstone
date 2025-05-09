<?php

namespace Modules\Umkm\Services;

use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\UmkmMBentuk;
use Modules\Umkm\Entities\UmkmMJenis;
use App\Services\Traits\ResponseFormatter;

class ReferensiUmkmService
{
    use ResponseFormatter;

    protected $models = [
        'bentuk' => UmkmMBentuk::class,
        'jenis' => UmkmMJenis::class,
    ];

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

    public function index()
    {
        $dataParam = request()->query('data');

        if (empty($dataParam)) {
            return response()->json($this->formatResponse(false, 400, "Parameter 'data' tidak boleh kosong"), 400);
        }

        $types = explode(',', $dataParam);
        $data = $this->service->getMultiple($types);

        $typesList = implode(', ', $types);
        $message = "Data {$typesList} berhasil diambil";

        return response()->json($this->formatResponse(true, 200, $message, $data), 200);
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
