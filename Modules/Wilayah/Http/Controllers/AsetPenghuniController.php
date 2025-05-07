<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Http\Requests\AsetPenghuni\CreateAsetPenghuniRequest;
use Modules\Wilayah\Http\Requests\AsetPenghuni\UpdateAsetPenghuniRequest;
use Modules\Wilayah\Services\AsetPenghuniService;
use App\Services\Traits\ResponseFormatter;

class AsetPenghuniController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(AsetPenghuniService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAll();
        return response()->json($this->formatResponse(true, 200, 'Data penghuni berhasil diambil', $data), 200);
    }

    public function byAset(Aset $aset)
    {
        $data = $this->service->getAllByAset($aset);
        return response()->json($this->formatResponse(true, 200, 'Data penghuni berhasil diambil', $data), 200);
    }

    public function show($id)
    {
        $data = $this->service->getById($id);
        return response()->json($this->formatResponse(true, 200, 'Data penghuni berhasil ditemukan', $data), 200);
    }

    public function store(CreateAsetPenghuniRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        $data = $this->service->store($aset, $validated['penghuni']);
        return response()->json($this->formatResponse(true, 200, "Data penghuni berhasil ditambah", $data), 201);
    }

    public function update(UpdateAsetPenghuniRequest $request, Aset $aset)
    {
        $validated = $request->validated();
        $data = $this->service->update($aset, $validated['penghuni']);
        return response()->json($this->formatResponse(true, 200, "Data penghuni berhasil diperbarui", $data), 200);
    }
}
