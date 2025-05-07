<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Services\AsetFotoService;
use App\Services\Traits\ResponseFormatter;

class AsetFotoController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(AsetFotoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAll();
        return response()->json($this->formatResponse(true, 200, "Data foto berhasil diambil", $data), 200);
    }

    public function byAset(Aset $aset)
    {
        $data = $this->service->getAllByAset($aset);
        return response()->json($this->formatResponse(true, 200, "Data foto berhasil ditemukan", $data), 200);
    }

    public function show($id)
    {
        $data = $this->service->getById($id);
        if ($data) {
            return response()->json($this->formatResponse(true, 200, "Data foto berhasil ditemukan", $data), 200);
        }
        return response()->json($this->formatResponse(false, 404, "Data foto tidak ditemukan"), 404);
    }
}
