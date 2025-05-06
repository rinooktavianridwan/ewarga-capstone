<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Http\Requests\Aset\CreateAsetRequest;
use Modules\Wilayah\Http\Requests\Aset\GetAllByNameRequest;
use Modules\Wilayah\Http\Requests\Aset\UpdateAsetRequest;
use Modules\Wilayah\Http\Requests\Aset\UpdateLokasiRequest;
use Modules\Wilayah\Services\AsetService;
use App\Services\Traits\ResponseFormatter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetController extends Controller
{
    use ResponseFormatter;

    protected AsetService $asetService;

    public function __construct(AsetService $asetService)
    {
        $this->asetService = $asetService;
    }

    public function index()
    {
        $data = $this->asetService->getAll();
        return response()->json($this->formatResponse(true, 200, 'Data aset berhasil diambil', $data));
    }

    public function show($id)
    {
        try {
            $data = $this->asetService->getById($id);
            return response()->json($this->formatResponse(true, 200, "Data aset dengan ID {$id} berhasil ditemukan", $data));
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 404, $e->getMessage()), 404);
        }
    }

    public function store(CreateAsetRequest $request)
    {
        $validated = $request->validated();
        $data = $this->asetService->create($validated);
        return response()->json($this->formatResponse(true, 201, 'Data aset berhasil dibuat', $data), 201);
    }

    public function update(UpdateAsetRequest $request, Aset $aset)
    {
        try {
            $validated = $request->validated();
            $data = $this->asetService->update($aset, $validated);
            return response()->json($this->formatResponse(true, 200, "Data aset dengan ID {$aset->id} berhasil diperbarui", $data));
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data aset tidak ditemukan"), 404);
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 500, $e->getMessage()), 500);
        }
    }

    public function destroy(Aset $aset)
    {
        try {
            $deletedAset = $this->asetService->delete($aset);
            return response()->json($this->formatResponse(true, 200, 'Data aset berhasil dihapus', $deletedAset));
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data aset dengan id {$aset->id} tidak ditemukan"), 404);
        }
    }

    public function showLokasi(Aset $aset)
    {
        try {
            $data = $this->asetService->getLokasi($aset);
            return response()->json($this->formatResponse(true, 200, 'Lokasi aset berhasil ditemukan', $data));
        } catch (\Exception $e) {
            return response()->json($this->formatResponse(false, 404, $e->getMessage()), 404);
        }
    }

    public function updateLokasi(UpdateLokasiRequest $request, Aset $aset)
    {
        try {
            $validated = $request->validated();
            $data = $this->asetService->updateLokasi($aset, $validated['latitude'], $validated['longitude']);
            return response()->json($this->formatResponse(true, 200, 'Lokasi aset berhasil diperbarui', $data));
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data aset dengan id {$aset->id} tidak ditemukan"), 404);
        }
    }

    public function searchByName(GetAllByNameRequest $request)
    {
        $validated = $request->validated();
        $data = $this->asetService->getAllByName($validated['name']);
        return response()->json($this->formatResponse(true, 200, 'Data aset berhasil ditemukan', $data));
    }

    public function getAllbyInstansi($instansiId)
    {
        try {
            $data = $this->asetService->getAllByInstansi($instansiId);
            return response()->json($this->formatResponse(true, 200, "Data aset berdasarkan instansi_id {$instansiId} berhasil ditemukan", $data));
        } catch (ModelNotFoundException $e) {
            return response()->json($this->formatResponse(false, 404, "Data aset berdasarkan instansi_id {$instansiId} tidak ditemukan"), 404);
        }
    }
}
