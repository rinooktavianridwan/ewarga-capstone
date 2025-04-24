<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\AsetMJenis;
use Modules\Wilayah\Http\Requests\AsetMJenis\CreateAsetMJenisRequest;
use Modules\Wilayah\Http\Requests\AsetMJenis\UpdateAsetMJenisRequest;
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

    public function store(CreateAsetMJenisRequest $request)
    {
        $validated = $request->validated();
        return response()->json($this->service->create($validated));
    }

    public function update(UpdateAsetMJenisRequest $request, AsetMJenis $asetMJenis)
    {
        $validated = $request->validated();
        return response()->json($this->service->update($asetMJenis, $validated));
    }

    public function destroy(AsetMJenis $asetMJenis)
    {
        return response()->json($this->service->delete($asetMJenis));
    }
}
