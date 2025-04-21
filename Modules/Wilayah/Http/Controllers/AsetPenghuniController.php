<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Services\AsetPenghuniService;

class AsetPenghuniController extends Controller
{
    protected $service;

    public function __construct(AsetPenghuniService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function byAset(Aset $aset)
    {
        return response()->json($this->service->getAllByAset($aset));
    }

    public function show($id)
    {
        return response()->json($this->service->getById($id));
    }

    public function store(Request $request, Aset $aset)
    {
        return response()->json($this->service->store($aset, $request->penghuni));
    }

    public function update(Request $request, Aset $aset)
    {
        return response()->json($this->service->update($aset, $request->penghuni));
    }

    public function destroy(Request $request, Aset $aset)
    {
        return response()->json([
            'deleted' => $this->service->delete($aset, $request->penghuni_ids),
        ]);
    }
}
