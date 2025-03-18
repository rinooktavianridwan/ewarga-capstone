<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Umkm\Services\UmkmService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
{
    protected $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    /**
     * Menampilkan daftar UMKM berdasarkan warga yang sedang login.
     */
    public function index()
    {
        try {
            $umkm = $this->umkmService->getAllUmkm();
            return response()->json(['umkm' => $umkm, 'message' => "helloooo"], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Menampilkan detail UMKM berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $umkm = $this->umkmService->getUmkmById($id);
            return response()->json(['umkm' => $umkm], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Menyimpan UMKM baru dengan validasi lengkap.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'jenis_usaha' => 'required|in:online,offline',
                'kontak' => 'required|string|max:20',
                'warga_id' => 'required|exists:warga,id',
                'alamat' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png|max:1024',
            ]);

            $umkm = $this->umkmService->createUmkm($request->all());

            return response()->json(['message' => 'UMKM berhasil ditambahkan', 'umkm' => $umkm], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Update UMKM berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            // Debugging awal untuk melihat request yang diterima
            if ($request->isJson()) {
                $data = $request->json()->all();
            } else {
                $data = $request->all();
            }

            if (empty($data)) {
                return response()->json(['message' => 'Data tidak ditemukan dalam request'], 400);
            }

            // Validasi input
            $validator = Validator::make($data, [
                'nama' => 'sometimes|required|string|max:255',
                'jenis_usaha' => 'sometimes|required|in:online,offline',
                'kontak' => 'sometimes|required|string|max:20',
                'alamat' => 'sometimes|required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Panggil service untuk update data
            $umkm = $this->umkmService->updateUmkm($id, $data);

            return response()->json(['message' => 'UMKM berhasil diperbarui', 'umkm' => $umkm], 200);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus UMKM berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $this->umkmService->deleteUmkm($id);
            return response()->json(['message' => 'UMKM berhasil dihapus'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
