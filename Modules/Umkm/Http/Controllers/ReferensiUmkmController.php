<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Umkm\Services\ReferensiUmkmService;

class ReferensiUmkmController extends Controller
{
    protected $service;

    public function __construct(ReferensiUmkmService $service)
    {
        $this->service = $service;
    }

    public function getBentukUsaha(): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getAllBentukUsaha()
        ]);
    }

    public function getJenisUsaha(): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getAllJenisUsaha()
        ]);
    }
}
