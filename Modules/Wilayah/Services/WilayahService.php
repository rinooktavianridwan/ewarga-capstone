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

        $currentMonthAsetCount = Aset::where('created_at', '>=', $currentMonth)->count();

        $percentageIncrease = $totalAset > 0
            ? ($currentMonthAsetCount / ($totalAset-$currentMonthAsetCount)) * 100
            : 0;

        return [
            'total_aset' => $totalAset,
            'percentage_increase' => $percentageIncrease,
        ];
    }
}
