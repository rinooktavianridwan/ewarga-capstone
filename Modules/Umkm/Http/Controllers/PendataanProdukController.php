<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Services\ProdukService;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Http\Requests\StorePendataanProdukRequest;
use Modules\Umkm\Http\Requests\UpdatePendataanProdukRequest;

class PendataanProdukController extends Controller
{
    protected ProdukService $service;

    public function __construct(ProdukService $service)
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

    public function store(StorePendataanProdukRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $produk = $this->service->store($validated, $request->file('foto', []));

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $produk,
        ], 201);
    }

    public function update(UpdatePendataanProdukRequest $request, UmkmProduk $produk): JsonResponse
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
