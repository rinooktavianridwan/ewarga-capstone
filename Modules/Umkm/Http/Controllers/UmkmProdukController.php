<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Http\Requests\UmkmProduk\CreateProdukRequest;
use Modules\Umkm\Http\Requests\UmkmProduk\UpdateProdukRequest;
use Modules\Umkm\Services\UmkmProdukService;


class UmkmProdukController extends Controller
{
    protected UmkmProdukService $service;

    public function __construct(UmkmProdukService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getFilteredProduk($request)
        ]);
    }

    public function show(UmkmProduk $produk): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getById($produk->id)
        ]);
    }

    public function store(CreateProdukRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $produk = $this->service->store($validated, $request->file('foto', []));

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $produk,
        ], 201);
    }

    public function update(UpdateProdukRequest $request, UmkmProduk $produk): JsonResponse
    {
        $validated = $request->validated();
        $updatedProduk = $this->service->update($produk->id, $validated, $request->file('foto', []));

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'data' => $updatedProduk,
        ]);
    }

    public function destroy(UmkmProduk $produk): JsonResponse
    {
        $this->service->delete($produk->id);
        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
