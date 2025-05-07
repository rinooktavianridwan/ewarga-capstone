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
        return response()->json($this->formatResponse(true, 200, 'Data aset berhasil diambil', $data), 200);
    }

    public function show($id)
    {
        $data = $this->asetService->getById($id);
        return response()->json($this->formatResponse(true, 200, "Data aset berhasil ditemukan", $data), 200);
    }

    public function store(CreateAsetRequest $request)
    {
        $validated = $request->validated();
        $data = $this->asetService->create($validated);
        return response()->json($this->formatResponse(true, 201, 'Data aset berhasil dibuat', $data), 201);
    }

    public function update(UpdateAsetRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        $data = $this->asetService->update($aset, $validated);
        return response()->json($this->formatResponse(true, 200, "Data aset berhasil diperbarui", $data), 200);
    }

    public function destroy(Aset $aset)
    {
        $deletedAset = $this->asetService->delete($aset);
        return response()->json($this->formatResponse(true, 200, 'Data aset berhasil dihapus', $deletedAset), 200);
    }

    public function showLokasi(Aset $aset)
    {
        $data = $this->asetService->getLokasi($aset);
        return response()->json($this->formatResponse(true, 200, 'Lokasi aset berhasil ditemukan', $data), 200);
    }

    public function updateLokasi(UpdateLokasiRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        $data = $this->asetService->updateLokasi($aset, $validated['latitude'], $validated['longitude']);
        return response()->json($this->formatResponse(true, 200, 'Lokasi aset berhasil diperbarui', $data), 200);
    }

    public function searchByName(GetAllByNameRequest $request)
    {
        $validated = $request->validated();
        $data = $this->asetService->getAllByName($validated['name']);
        return response()->json($this->formatResponse(true, 200, 'Data aset berhasil ditemukan', $data), 200);
    }

    public function getAllbyInstansi($instansiId)
    {
        $data = $this->asetService->getAllByInstansi($instansiId);
        return response()->json($this->formatResponse(true, 200, "Data aset berhasil ditemukan", $data), 200);
    }
}
