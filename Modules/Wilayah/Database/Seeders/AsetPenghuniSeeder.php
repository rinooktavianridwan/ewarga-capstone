<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsetPenghuniSeeder extends Seeder
{
    public function run()
    {
        $asetPenghuniData = [
            [
                'id' => 1,
                'warga_id' => 2,
                'aset_m_status_id' => 1,
                'aset_id' => 1
            ],
            [
                'id' => 2,
                'warga_id' => 6,
                'aset_m_status_id' => 1,
                'aset_id' => 1
            ],
            [
                'id' => 3,
                'warga_id' => 4,
                'aset_m_status_id' => 1,
                'aset_id' => 2
            ],
            [
                'id' => 4,
                'warga_id' => 16,
                'aset_m_status_id' => 2,
                'aset_id' => 4
            ],
            [
                'id' => 5,
                'warga_id' => 20,
                'aset_m_status_id' => 2,
                'aset_id' => 4
            ],
        ];

        DB::table('aset_penghuni')->insert($asetPenghuniData);
    }
}
