<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Http\Requests\UmkmProduk\CreateProdukRequest;
use Modules\Umkm\Http\Requests\UmkmProduk\UpdateProdukRequest;
use Modules\Umkm\Http\Requests\UmkmProduk\GetFilteredRequest;
use Modules\Umkm\Services\UmkmService;
use Modules\Umkm\Services\UmkmProdukService;
use App\Services\Traits\ResponseFormatter;

class UmkmProdukController extends Controller
{
    use ResponseFormatter;

    protected UmkmService $umkmService;
    protected UmkmProdukService $umkmProdukService;

    public function __construct(UmkmService $umkmService, UmkmProdukService $umkmProdukService)
    {
        $this->umkmService = $umkmService;
        $this->umkmProdukService = $umkmProdukService;
    }

    public function index(GetFilteredRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmProdukService->getFiltered($validated);
        return response()->json($this->formatResponse(true, 201, 'Data produk berhasil diambil', $data), 201);
    }

    public function show($id): JsonResponse
    {
        $data = $this->umkmProdukService->getById($id);
        return response()->json($this->formatResponse(true, 200, "Data produk berhasil ditemukan", $data), 200);
    }

    public function store(CreateProdukRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmProdukService->create($validated);

        return response()->json($this->formatResponse(true, 201, 'Data produk berhasil dibuat', $data), 201);
    }

    public function update(UpdateProdukRequest $request, UmkmProduk $produk): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->umkmProdukService->update($produk, $validated);
        return response()->json($this->formatResponse(true, 200, "Data produk berhasil diperbarui", $data), 200);
    }

    public function destroy(UmkmProduk $produk): JsonResponse
    {
        $deletedProduk = $this->umkmProdukService->delete($produk);
        return response()->json($this->formatResponse(true, 200, 'Data produk berhasil dihapus', $deletedProduk), 200);
    }
}
