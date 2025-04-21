<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Services\AsetMStatusService;
use Modules\Wilayah\Entities\AsetMStatus;

class AsetMStatusController extends Controller
{
    protected $service;

    public function __construct(AsetMStatusService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show(int $id)
    {
        return response()->json($this->service->getById($id));
    }

    public function store(Request $request)
    {
        return response()->json($this->service->create($request->all()));
    }

    public function update(Request $request, AsetMStatus $asetMStatus)
    {
        return response()->json($this->service->update($asetMStatus, $request->all()));
    }

    public function destroy(AsetMStatus $asetMStatus)
    {
        $this->service->delete($asetMStatus);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
