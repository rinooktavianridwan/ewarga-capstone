<?php

namespace Modules\Wilayah\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Services\AsetFotoService;

class AsetFotoController extends Controller
{
    protected $service;

    public function __construct(AsetFotoService $service)
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

    public function store(Request $request, Aset $aset)
    {
        $this->service->store($aset, $request->file('fotos'), $request->instansi_id, $request->warga_id);
        return response()->json(['message' => 'Foto berhasil ditambahkan.']);
    }

    public function destroy(Request $request, Aset $aset)
    {
        $this->service->delete($aset, $request->foto_ids);
        return response()->json(['message' => 'Foto berhasil dihapus.']);
    }
}
