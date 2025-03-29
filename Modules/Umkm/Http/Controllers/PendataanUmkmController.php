<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Umkm\Services\UmkmService;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;

class PendataanUmkmController extends Controller
{
    protected UmkmService $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    public function index(Request $request): JsonResponse
    {
        $umkmList = $this->umkmService->getFilteredUmkm($request);
        return response()->json(['data' => $umkmList]);
    }

    public function show($id): JsonResponse
    {
        $umkm = $this->umkmService->getUmkmDetailById($id);
        return response()->json(['data' => $umkm]);
    }


    public function store(Request $request): JsonResponse
    {
        if ($request->hasFile('foto') && count($request->file('foto')) > 5) {
            return response()->json([
                'message' => 'Foto yang dikirim tidak boleh lebih dari 5.'
            ], 422);
        }

        $instansiId = $request->input('instansi_id');
        $wargaIds = $request->input('warga_ids');

        // Validasi konsistensi instansi_id
        $invalidWarga = Warga::whereIn('id', $wargaIds)
            ->where(function ($q) use ($instansiId) {
                $q->whereNull('instansi_id')->orWhere('instansi_id', '!=', $instansiId);
            })->exists();

        if ($invalidWarga) {
            return response()->json([
                'message' => 'Semua warga harus memiliki instansi_id yang sama dengan instansi_id yang dipilih'
            ], 400);
        }

        $data = $request->all();
        $fotoFiles = $request->file('foto');

        $umkm = $this->umkmService->createUmkm($data, $fotoFiles);

        return response()->json([
            'message' => 'UMKM berhasil ditambahkan',
            'data' => $umkm
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        if ($request->hasFile('foto') && count($request->file('foto')) > 5) {
            return response()->json([
                'message' => 'Foto yang dikirim tidak boleh lebih dari 5.'
            ], 422);
        }

        if ($request->filled('instansi_id') && $request->filled('warga_ids')) {
            $instansiId = $request->input('instansi_id');
            $wargaIds = $request->input('warga_ids');

            $invalidWarga = Warga::whereIn('id', $wargaIds)
                ->where(function ($q) use ($instansiId) {
                    $q->whereNull('instansi_id')->orWhere('instansi_id', '!=', $instansiId);
                })->exists();

            if ($invalidWarga) {
                return response()->json([
                    'message' => 'Semua warga harus memiliki instansi_id yang sama dengan instansi_id yang dipilih'
                ], 400);
            }
        }

        $data = $request->all();
        $fotoFiles = $request->file('foto');

        $umkm = $this->umkmService->updateUmkm($id, $data, $fotoFiles);

        return response()->json([
            'message' => 'UMKM berhasil diperbarui',
            'data' => $umkm
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->umkmService->deleteUmkm($id);
        return response()->json(['message' => 'UMKM berhasil dihapus']);
    }
}
