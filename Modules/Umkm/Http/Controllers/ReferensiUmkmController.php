<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Http\Requests\MasterDataRequest;
use Modules\Umkm\Services\ReferensiUmkmService;
use App\Services\Traits\ResponseFormatter;


class ReferensiUmkmController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(ReferensiUmkmService $service)
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

    public function store(MasterDataRequest $request, string $type): JsonResponse
    {
        try {
            $data = $this->service->create($type, $request->validated());
            return response()->json($this->formatResponse(true, 201, "Data $type berhasil dibuat", $data), 201);
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 500, $e->getMessage()), 500);
        }
    }

    public function show(string $type, int $id): JsonResponse
    {
        try {
            $data = $this->service->getById($type, $id);
            return response()->json($this->formatResponse(true, 200, "Data $type berhasil diambil", $data), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data $type tidak ditemukan"), 404);
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 500, $e->getMessage()), 500);
        }
    }

    public function update(MasterDataRequest $request, string $type, int $id): JsonResponse
    {
        try {
            $data = $this->service->update($type, $id, $request->validated());
            return response()->json($this->formatResponse(true, 200, "Data $type berhasil diperbarui", $data), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data $type tidak ditemukan"), 404);
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 500, $e->getMessage()), 500);
        }
    }

    public function destroy(string $type, int $id): JsonResponse
    {
        try {
            $data = $this->service->delete($type, $id);
            return response()->json($this->formatResponse(true, 200, "Data $type berhasil dihapus", $data), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data $type tidak ditemukan"), 404);
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 500, $e->getMessage()), 500);
        }
    }
}
