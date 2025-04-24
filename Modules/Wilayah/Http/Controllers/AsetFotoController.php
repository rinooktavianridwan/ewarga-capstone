<?php

namespace Modules\Wilayah\Http\Controllers;

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
}
