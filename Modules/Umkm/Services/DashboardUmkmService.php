<?php

namespace Modules\Umkm\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Entities\UmkmJenisUsaha;
use Modules\Umkm\Entities\UmkmProduk;

class DashboardUmkmService
{
    public function getDashboardData(): array
    {
        return DB::transaction(function () {
            $totalUmkm = Umkm::count();
            $totalProduk = UmkmProduk::count();

            $online = UmkmJenisUsaha::where('nama', 'like', '%Online%')->first();
            $offline = UmkmJenisUsaha::where('nama', 'like', '%Offline%')->first();
            $hybrid = UmkmJenisUsaha::where('nama', 'like', '%Hybrid%')->first();

            return [
                'total_umkm' => $totalUmkm,
                'total_produk' => $totalProduk,
                'umkm_online' => $online?->umkms()->count() ?? 0,
                'umkm_offline' => $offline?->umkms()->count() ?? 0,
                'umkm_hybrid' => $hybrid?->umkms()->count() ?? 0,
            ];
        });
    }

    public function getLatestUmkm(int $limit = 5): array
    {
        return DB::transaction(function () use ($limit) {
            return Umkm::with(['jenisUsaha', 'produks'])
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($umkm) {
                    return [
                        'nama_umkm' => $umkm->nama,
                        'jenis_usaha' => $umkm->jenisUsaha->nama ?? '-',
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
                )
                ->whereYear('created_at', $tahun);

            if ($bentukUsahaId) {
                $query->where('umkm_M_bentuk_id', $bentukUsahaId);
            }

            if ($jenisUsahaId) {
                $query->where('umkm_M_jenis_id', $jenisUsahaId);
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
