<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AsetMJenisSeeder extends Seeder
{
    public function run()
    {
        $asetMJenisData = [
            [
                'id' => 1,
                'nama' => 'Aset Pribadi',
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 2,
                'nama' => 'Fasilitas Umum',
                'created_at' => Carbon::now()->subMonth(),
            ]
        ];

        DB::table('aset_m_jenis')->insert($asetMJenisData);
    }
}
