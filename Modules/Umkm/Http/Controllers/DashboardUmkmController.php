<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Umkm\Services\DashboardUmkmService;

class DashboardUmkmController extends Controller
{
    protected DashboardUmkmService $dashboardService;

    public function __construct(DashboardUmkmService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboardService->getDashboardData()
        ]);
    }

    public function latestUmkm(): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboardService->getLatestUmkm()
        ]);
    }

    public function growthByMonth(Request $request): JsonResponse
    {
        $tahun = $request->query('tahun', now()->year);
        $bentukUsahaId = $request->query('bentuk_usaha_id');
        $jenisUsahaId = $request->query('jenis_usaha_id');

        $data = $this->dashboardService->getMonthlyGrowth((int) $tahun, $bentukUsahaId, $jenisUsahaId);

        return response()->json(['data' => $data]);
    }
}
