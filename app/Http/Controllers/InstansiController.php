<?php

namespace App\Http\Controllers;

use App\Services\InstansiService;
use App\Services\Traits\ResponseFormatter;

class InstansiController extends Controller
{
    use ResponseFormatter;

    protected $service;

    public function __construct(InstansiService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAll();
        return response()->json($this->formatResponse(true, 200, 'Data instansi berhasil diambil', $data));
    }

    public function show($id)
    {
        $data = $this->service->getById($id);
        return response()->json($this->formatResponse(true, 200, 'Data instansi berhasil ditemukan', $data));
    }
}
