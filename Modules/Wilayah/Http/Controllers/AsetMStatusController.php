<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Http\Requests\AsetMStatus\CreateAsetMStatusRequest;
use Modules\Wilayah\Http\Requests\AsetMStatus\UpdateAsetMStatusRequest;
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

    public function store(CreateAsetMStatusRequest $request)
    {
        $validated = $request->validated();
        return response()->json($this->service->create($validated->all()));
    }

    public function update(UpdateAsetMStatusRequest $request, AsetMStatus $asetMStatus)
    {
        $validated = $request->validated();
        return response()->json($this->service->update($asetMStatus, $validated->all()));
    }

    public function destroy(AsetMStatus $asetMStatus)
    {
        return response()->json($this->service->delete($asetMStatus));
    }
}
