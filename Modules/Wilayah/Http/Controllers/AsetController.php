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
        return response()->json($this->asetService->getAll());
    }

    public function show($id)
    {
        return response()->json($this->asetService->getById($id));
    }

    public function store(Request $request)
    {
        return response()->json($this->asetService->create($request->all()), 201);
    }

    public function update(Request $request, Aset $aset)
    {
        return response()->json($this->asetService->update($aset, $request->all()));
    }

    public function destroy(Aset $aset)
    {
        return response()->json($this->asetService->delete($aset));
    }

    public function showLokasi(Aset $aset)
    {
        return response()->json($this->asetService->getLokasi($aset));
    }

    public function updateLokasi(Request $request, Aset $aset)
    {
        return response()->json($this->asetService->updateLokasi($aset, $request->latitude, $request->longitude));
    }

    public function searchByName(Request $request)
    {
        return response()->json($this->asetService->getAllByName($request->name));
    }
}
