<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahAsetPenghuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wilayahPenghuniData = [
            [
                'id' => 3,
                'warga_id' => 3,
                'aset_m_status_id' => 1,
                'aset_id' => 3
            ],
            [
                'id' => 4,
                'warga_id' => 4,
                'aset_m_status_id' => 1,
                'aset_id' => 1
            ],
            [
                'id' => 6,
                'warga_id' => 6,
                'aset_m_status_id' => 1,
                'aset_id' => 4
            ],
            [
                'id' => 8,
                'warga_id' => 8,
                'aset_m_status_id' => 1,
                'aset_id' => 5
            ],
        ];

        DB::table('aset_penghuni')->insert($wilayahPenghuniData);
    }
}
