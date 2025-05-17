<?php

namespace App\Http\Controllers;

use App\Services\WargaService;
use App\Services\Traits\ResponseFormatter;
use App\Http\Requests\RegisterWargaRequest;
 use App\Http\Requests\UpdateWargaRequest;

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
        return response()->json($this->formatResponse(true, 200, 'Data warga berhasil diambil', $data), 200);
    }

    public function show($id)
    {
        $data = $this->service->getById($id);
        return response()->json($this->formatResponse(true, 200, 'Data warga berhasil ditemukan', $data), 200);
    }

    public function register(RegisterWargaRequest $request)
    {
        $user = auth()->user();
        $data = array_merge($request->validated(), ['user_id' => $user->id]);
        $warga = $this->service->registerWarga($data);

        return response()->json($this->formatResponse(true, 201, 'Warga berhasil didaftarkan', $warga), 201);
    }

    public function update(UpdateWargaRequest $request, $id)
    {
        $foto = $request->file('foto');
        $data = $request->except('foto');

        $warga = $this->service->updateWarga($id, $data, $foto);

        return response()->json($this->formatResponse(true, 200, 'Data warga berhasil diperbarui', $warga), 200);
    }
}
