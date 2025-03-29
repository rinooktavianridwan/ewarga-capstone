<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Services\ProdukService;

class PendataanProdukController extends Controller
{
    protected $service;

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

    public function show($id): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getById($id)
        ]);
    }

    public function store(Request $request): JsonResponse
    {

        if ($request->hasFile('foto') && count($request->file('foto')) > 5) {
            return response()->json([
                'message' => 'Foto yang dikirim tidak boleh lebih dari 5.'
            ], 422);
        }

        $validated = $request->validate([
            'umkm_id' => 'required|exists:umkm,id',
            'instansi_id' => 'required|exists:instansi,id',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'harga' => 'required|integer',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $produk = $this->service->store($validated, $request->file('foto', []));
            return response()->json([
                'message' => 'Produk berhasil ditambahkan.',
                'data' => $produk,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        if ($request->hasFile('foto') && count($request->file('foto')) > 5) {
            return response()->json([
                'message' => 'Foto yang dikirim tidak boleh lebih dari 5.'
            ], 422);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'keterangan' => 'nullable|string',
            'harga' => 'sometimes|required|integer',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);


        try {
            $produk = $this->service->update($id, $validated, $request->file('foto', []));
            return response()->json([
                'message' => 'Produk berhasil diperbarui.',
                'data' => $produk,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json([
                'message' => 'Produk berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
