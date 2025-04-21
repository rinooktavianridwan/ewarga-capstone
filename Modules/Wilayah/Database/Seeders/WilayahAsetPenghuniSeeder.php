<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahFotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Data dummy aset penghuni
        $wilayahFotoData = [
            [
                'id' => 3,
                'warga_id' => 3,
                'aset_m_status_id' => 3,
                'aset_id' => 3
            ],
            [
                'id' => 4,
                'warga_id' => 4,
                'aset_m_status_id' => 4,
                'aset_id' => 4
            ],
            [
                'id' => 6,
                'warga_id' => 6,
                'aset_m_status_id' => 6,
                'aset_id' => 6
            ],
            [
                'id' => 8,
                'warga_id' => 8,
                'aset_m_status_id' => 8,
                'aset_id' => 8
            ],
        ];

        DB::table('wilayah_foto')->insert($wilayahFotoData);
    }
}