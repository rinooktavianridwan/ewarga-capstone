<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Http\Requests\Aset\CreateAsetRequest;
use Modules\Wilayah\Http\Requests\Aset\GetAllByNameRequest;
use Modules\Wilayah\Http\Requests\Aset\UpdateAsetRequest;
use Modules\Wilayah\Http\Requests\Aset\UpdateLokasiRequest;
use Modules\Wilayah\Services\AsetService;

class AsetController extends Controller
{
    protected AsetService $asetService;

    public function __construct(AsetService $asetService)
    {
        $this->asetService = $asetService;
    }

    public function index()
    {
        return response()->json($this->asetService->getAll());
    }

    public function show($id)
    {
        return response()->json($this->asetService->getById($id));
    }

    public function store(CreateAsetRequest $request)
    {
        $validated = $request->validated();
        return response()->json($this->asetService->create($validated), 201);
    }

    public function update(UpdateAsetRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        return response()->json($this->asetService->update($aset, $validated));
    }

    public function destroy(Aset $aset)
    {
        return response()->json($this->asetService->delete($aset));
    }

    public function showLokasi(Aset $aset)
    {
        return response()->json($this->asetService->getLokasi($aset));
    }

    public function updateLokasi(UpdateLokasiRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        return response()->json($this->asetService->updateLokasi($aset, $validated['latitude'], $validated['longitude']));
    }

    public function searchByName(GetAllByNameRequest $request)
    {
        $validated = $request->validated();
        return response()->json($this->asetService->getAllByName($validated['name']));
    }

    public function getAllbyInstansi($instansiId)
    {
        return response()->json($this->asetService->getAllByInstansi($instansiId));
    }
}
