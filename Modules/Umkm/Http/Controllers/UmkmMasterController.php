<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Http\Requests\UmkmMaster\UmkmMasterRequest;
use Modules\Umkm\Services\UmkmMasterService;
use App\Services\Traits\ResponseFormatter;

class UmkmMasterController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(UmkmMasterService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
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

    public function show(string $type, int $id): JsonResponse
    {
        $data = $this->service->getById($type, $id);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil diambil", $data), 200);
    }

    public function store(UmkmMasterRequest $request, string $type): JsonResponse
    {
        $data = $request->validated();
        $createdData = $this->service->create($type, $data);
        return response()->json($this->formatResponse(true, 201, "Data $type berhasil dibuat", $createdData), 201);
    }

    public function update(UmkmMasterRequest $request, string $type, int $id): JsonResponse
    {
        $data = $request->validated();
        $updatedData = $this->service->update($type, $id, $data);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil diperbarui", $updatedData), 200);
    }

    public function destroy(string $type, int $id): JsonResponse
    {
        $deletedData = $this->service->delete($type, $id);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil dihapus", $deletedData), 200);
    }
}
