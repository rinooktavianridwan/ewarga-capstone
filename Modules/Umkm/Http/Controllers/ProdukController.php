<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Umkm\Services\ProdukService;
use Illuminate\Validation\ValidationException;

class ProdukController extends Controller
{
    protected $produkService;

    public function __construct(ProdukService $produkService)
    {
        $this->produkService = $produkService;
    }

    /**
     * Menampilkan semua produk berdasarkan UMKM ID.
     */
    public function index($id)
    {
        $produk = $this->produkService->getAllProdukByUmkmId($id);

        // Jika tidak ada produk, tampilkan pesan 404
        if (is_array($produk) && isset($produk['message'])) {
            return response()->json($produk, 404);
        }

        return response()->json($produk, 200);
    }

    /**
     * Menampilkan produk berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $produk = $this->produkService->getProdukById($id);
            return response()->json($produk, 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Menambahkan produk baru untuk UMKM.
     */
    public function store(Request $request)
    {
        try {
            $produk = $this->produkService->createProduk($request->all());
            return response()->json(['message' => 'Produk berhasil ditambahkan', 'produk' => $produk], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Memperbarui produk berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $produk = $this->produkService->updateProduk($id, $request->all());
            return response()->json(['message' => 'Produk berhasil diperbarui', 'produk' => $produk], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Menghapus produk berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $this->produkService->deleteProduk($id);
            return response()->json(['message' => 'Produk berhasil dihapus'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
