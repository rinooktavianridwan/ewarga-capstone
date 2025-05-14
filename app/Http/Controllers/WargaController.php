<?php

namespace App\Http\Controllers;

use App\Services\WargaService;
use App\Services\Traits\ResponseFormatter;

class WargaController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(WargaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAll();
        return response()->json($this->formatResponse(true, 200, 'Data warga berhasil diambil', $data));
    }

    public function show($id)
    {
        $data = $this->service->getById($id);
        return response()->json($this->formatResponse(true, 200, 'Data warga berhasil ditemukan', $data));
    }
}
