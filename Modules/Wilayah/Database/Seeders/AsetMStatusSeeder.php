<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AsetMStatusSeeder extends Seeder
{
    public function run()
    {
        $asetMStatusData = [
            [
                'id' => 1,
                'nama' => 'Sesuai KK',
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 2,
                'nama' => 'Tidak Sesuai KK',
                'created_at' => Carbon::now()->subMonth(),
            ],
        ];

        DB::table('aset_m_status')->insert($asetMStatusData);
    }
}
