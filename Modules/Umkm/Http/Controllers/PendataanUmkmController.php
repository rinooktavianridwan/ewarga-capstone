<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Services\UmkmService;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Http\Requests\StorePendataanUmkmRequest;
use Modules\Umkm\Http\Requests\UpdatePendataanUmkmRequest;
use Modules\Umkm\Http\Requests\UmkmFilterRequest;

class PendataanUmkmController extends Controller
{
    protected UmkmService $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    public function index(UmkmFilterRequest $request): JsonResponse
    {
        $umkmList = $this->umkmService->getFilteredUmkm($request);
        return response()->json(['data' => $umkmList]);
    }

    public function show(Umkm $umkm): JsonResponse
    {
        $detail = $this->umkmService->getUmkmDetailById($umkm->id);
        return response()->json(['data' => $detail]);
    }

    public function store(StorePendataanUmkmRequest $request): JsonResponse
    {
        $data = $request->validated();
        $fotoFiles = $request->file('foto');

        $umkm = $this->umkmService->createUmkmWithValidation($data, $fotoFiles);

        return response()->json([
            'message' => 'UMKM berhasil ditambahkan',
            'data' => $umkm
        ], 201);
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
