<?php

namespace Modules\Umkm\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Entities\UmkmMJenis;
use Modules\Umkm\Entities\UmkmProduk;

class DashboardService
{
    public function getDashboardData(): array
    {
        return DB::transaction(function () {
            $totalUmkm = Umkm::count();
            $totalProduk = UmkmProduk::count();

            $jenisList = UmkmMJenis::withCount('umkmJenis')->get();

            $jenisStatistik = $jenisList->mapWithKeys(function ($jenis) {
                return [
                    'umkm_' . strtolower(str_replace(' ', '_', $jenis->nama)) => $jenis->umkm_jenis_count,
                ];
            });

            return array_merge([
                'total_umkm' => $totalUmkm,
                'total_produk' => $totalProduk,
            ], $jenisStatistik->toArray());
        });
    }

    public function getLatestUmkm(int $limit = 5): array
    {
        return DB::transaction(function () use ($limit) {
            return Umkm::with(['jenis', 'produks'])
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($umkm) {
                    return [
                        'nama_umkm' => $umkm->nama,
                        'jenis_usaha' => $umkm->jenis->nama ?? '-',
                        'jumlah_produk' => $umkm->produks->count(),
                    ];
                })
                ->toArray();
        });
    }

    public function getMonthlyGrowth(int $tahun, $bentukUsahaId = null, $jenisUsahaId = null): array
    {
        return DB::transaction(function () use ($tahun, $bentukUsahaId, $jenisUsahaId) {
            $query = Umkm::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )->whereYear('created_at', $tahun);

            if ($bentukUsahaId) {
                $query->where('umkm_m_bentuk_id', $bentukUsahaId);
            }

            if ($jenisUsahaId) {
                $query->where('umkm_m_jenis_id', $jenisUsahaId);
            }

            $result = $query->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $growth = [];

            for ($i = 1; $i <= 12; $i++) {
                $growth[] = [
                    'month' => Carbon::create()->month($i)->format('M'),
                    'total' => $result[$i]->total ?? 0,
                ];
            }

            return $growth;
        });
    }
}
