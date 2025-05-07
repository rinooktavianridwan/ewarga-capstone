<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Services\AsetMasterService;
use Modules\Wilayah\Http\Requests\AsetMaster\AsetMasterRequest;
use App\Services\Traits\ResponseFormatter;

class AsetMasterController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(AsetMasterService $service)
    {
        $this->service = $service;
    }
    public function index(string $type)
    {
        $data = $this->service->getAll($type);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil diambil", $data), 200);
    }

    public function show(string $type, int $id)
    {
        $data = $this->service->getById($type, $id);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil diambil", $data), 200);
    }

    public function store(AsetMasterRequest $request, string $type)
    {
        $data = $request->validated();
        $createdData = $this->service->create($type, $data);
        return response()->json($this->formatResponse(true, 201, "Data $type berhasil dibuat", $createdData), 201);
    }

    public function update(AsetMasterRequest $request, string $type, int $id)
    {
        $data = $request->validated();
        $updatedData = $this->service->update($type, $id, $data);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil diperbarui", $updatedData), 200);
    }

    public function destroy(string $type, int $id)
    {
        $deletedData = $this->service->delete($type, $id);
        return response()->json($this->formatResponse(true, 200, "Data $type berhasil dihapus", $deletedData), 200);
    }
}
