<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AsetPenghuniSeeder extends Seeder
{
    public function run()
    {
        $asetPenghuniData = [
            [
                'id' => 1,
                'warga_id' => 2,
                'aset_m_status_id' => 1,
                'aset_id' => 1,
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 2,
                'warga_id' => 6,
                'aset_m_status_id' => 1,
                'aset_id' => 1,
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 3,
                'warga_id' => 4,
                'aset_m_status_id' => 1,
                'aset_id' => 2,
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 4,
                'warga_id' => 16,
                'aset_m_status_id' => 2,
                'aset_id' => 4,
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 5,
                'warga_id' => 20,
                'aset_m_status_id' => 2,
                'aset_id' => 4,
                'created_at' => Carbon::now()->subMonth(),
            ],
        ];

        DB::table('aset_penghuni')->insert($asetPenghuniData);
    }
}
