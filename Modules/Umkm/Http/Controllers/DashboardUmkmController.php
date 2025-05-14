<?php

namespace Modules\Umkm\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Umkm\Services\DashboardService;
use App\Services\Traits\ResponseFormatter;

class DashboardUmkmController extends Controller
{
    use ResponseFormatter;

    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData();
        return response()->json($this->formatResponse(true, 200, 'Data umkm berhasil diambil', $data), 200);
    }

    public function latestUmkm(): JsonResponse
    {
        $data = $this->dashboardService->getLatestUmkm();
        return response()->json($this->formatResponse(true, 200, 'Data umkm berhasil diambil', $data), 200);
    }

    public function growthByMonth(Request $request): JsonResponse
    {
        $tahun = $request->query('tahun', now()->year);
        $bentukUsahaId = $request->query('bentuk_usaha_id');
        $jenisUsahaId = $request->query('jenis_usaha_id');

        $data = $this->dashboardService->getMonthlyGrowth((int) $tahun, $bentukUsahaId, $jenisUsahaId);

        return response()->json($this->formatResponse(true, 200, 'Data umkm berhasil diambil', $data), 200);
    }
}
