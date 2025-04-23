<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\Aset;
use Illuminate\Support\Carbon;

class WilayahService
{
    public function getAsetStatistics(): array
    {
        $totalAset = Aset::count();

        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthAsetCount = Aset::where('created_at', '>=', $currentMonth)->count();
        $lastMonthAsetCount = Aset::whereBetween('created_at', [$lastMonth, $currentMonth->subSecond()])->count();

        $percentageIncrease = $lastMonthAsetCount > 0
            ? (($currentMonthAsetCount - $lastMonthAsetCount) / $lastMonthAsetCount) * 100
            : 0;

        return [
            'total_aset' => $totalAset,
            'percentage_increase' => $percentageIncrease,
        ];
    }
}
