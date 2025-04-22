<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\AsetMJenis;
use Modules\Wilayah\Services\AsetMJenisService;

class AsetMJenisController extends Controller
{
    protected $service;

    public function __construct(AsetMJenisService $service)
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

    public function update(Request $request, AsetMJenis $asetMJenis)
    {
        return response()->json($this->service->update($asetMJenis, $request->all()));
    }

    public function destroy(AsetMJenis $asetMJenis)
    {
        return response()->json($this->service->delete($asetMJenis));
    }
}
