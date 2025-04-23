<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsetMStatusSeeder extends Seeder
{
    public function run()
    {
        $asetMStatusData = [
            [
                'id' => 1,
                'nama' => 'Sesuai KK'
            ],
            [
                'id' => 2,
                'nama' => 'Tidak Sesuai KK'
            ],
        ];

        DB::table('aset_m_status')->insert($asetMStatusData);
    }
}
