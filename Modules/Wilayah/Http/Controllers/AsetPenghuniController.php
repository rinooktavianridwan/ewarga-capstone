<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Http\Requests\AsetPenghuni\CreateAsetPenghuniRequest;
use Modules\Wilayah\Http\Requests\AsetPenghuni\UpdateAsetPenghuniRequest;
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

    public function store(CreateAsetPenghuniRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        return response()->json($this->service->store($aset, $validated['penghuni']), 201);
    }

    public function update(UpdateAsetPenghuniRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        return response()->json($this->service->update($aset, $validated['penghuni']));
    }
}
