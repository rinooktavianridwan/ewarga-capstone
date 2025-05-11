<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\Traits\ResponseFormatter;
use Modules\Umkm\Http\Requests\CreateUmkmRequest;
use Modules\Umkm\Services\UmkmService;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Http\Requests\UpdatePendataanUmkmRequest;
use Modules\Umkm\Http\Requests\GetFilteredRequest;

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

    public function update(UpdatePendataanUmkmRequest $request, Umkm $umkm): JsonResponse
    {
        $data = $request->validated();
        $fotoFiles = $request->file('foto');

        $updatedUmkm = $this->umkmService->updateUmkmWithValidation($umkm->id, $data, $fotoFiles);

        return response()->json([
            'message' => 'UMKM berhasil diperbarui',
            'data' => $updatedUmkm
        ]);
    }

    public function destroy(Umkm $umkm): JsonResponse
    {
        $this->umkmService->deleteUmkm($umkm->id);
        return response()->json(['message' => 'UMKM berhasil dihapus']);
    }
}
