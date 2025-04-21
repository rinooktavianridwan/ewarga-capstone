<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
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
        $asets = $this->asetService->getAll();
        return response()->json($asets);
    }

    public function show($id)
    {
        $aset = $this->asetService->getById($id);
        return response()->json($aset);
    }

    public function store(Request $request)
    {
        $aset = $this->asetService->create($request->all());
        return response()->json($aset, 201);
    }

    public function update(Request $request, Aset $aset)
    {
        $updated = $this->asetService->update($aset, $request->all());
        return response()->json($updated);
    }

    public function destroy(Aset $aset)
    {
        $this->asetService->delete($aset);
        return response()->json(['message' => 'Aset berhasil dihapus.']);
    }

    public function updateLokasi(Request $request, Aset $aset)
    {
        $updated = $this->asetService->updateLokasi($aset, $request->latitude, $request->longitude);
        return response()->json($updated);
    }

    public function searchByName(Request $request)
    {
        $asets = $this->asetService->getAllByName($request->name);
        return response()->json($asets);
    }
}
