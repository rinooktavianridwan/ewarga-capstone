<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Services\AsetMasterService;
use Modules\Wilayah\Http\Requests\AsetMaster\AsetMasterRequest;

class AsetMasterController extends Controller
{
    protected $service;

    public function __construct(AsetMasterService $service)
    {
        $this->service = $service;
    }
    public function index(string $type)
    {
        return response()->json($this->service->getAll($type));
    }

    public function show(string $type, int $id)
    {
        return response()->json($this->service->getById($type, $id));
    }

    public function store(AsetMasterRequest $request, string $type)
    {
        $data = $request->validated();
        return response()->json($this->service->create($type, $data));
    }

    public function update(AsetMasterRequest $request, string $type, int $id)
    {
        $data = $request->validated();
        return response()->json($this->service->update($type, $id, $data));
    }

    public function destroy(string $type, int $id)
    {
        return response()->json($this->service->delete($type, $id));
    }
}
