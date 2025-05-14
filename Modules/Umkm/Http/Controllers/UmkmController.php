<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Services\UmkmService;
use Modules\Umkm\Http\Requests\Umkm\CreateUmkmRequest;
use Modules\Umkm\Http\Requests\Umkm\UpdateUmkmRequest;
use Modules\Umkm\Http\Requests\Umkm\GetFilteredRequest;
use App\Services\Traits\ResponseFormatter;

class UmkmController extends Controller
{
    use ResponseFormatter;

    protected $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    public function index(GetFilteredRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmService->getFiltered($validated);
        return response()->json($this->formatResponse(true, 201, 'Data umkm berhasil diambil', $data), 201);
    }

    public function show($id): JsonResponse
    {
        $data = $this->umkmService->getById($id);
        return response()->json($this->formatResponse(true, 200, "Data umkm berhasil ditemukan", $data), 200);
    }

    public function store(CreateUmkmRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmService->create($validated);

        return response()->json($this->formatResponse(true, 201, 'Data umkm berhasil dibuat', $data), 201);
    }

    public function update(UpdateUmkmRequest $request, Umkm $umkm): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmService->update($umkm, $validated);
        return response()->json($this->formatResponse(true, 200, "Data umkm berhasil diperbarui", $data), 200);
    }

    public function destroy(Umkm $umkm): JsonResponse
    {
        $deletedUmkm = $this->umkmService->delete($umkm);
        return response()->json($this->formatResponse(true, 200, 'Data umkm berhasil dihapus', $deletedUmkm), 200);
    }
}
