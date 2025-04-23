<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsetMJenisSeeder extends Seeder
{
    public function run()
    {
        $asetMJenisData = [
            [
                'id' => 1,
                'nama' => 'Aset Pribadi'
            ],
            [
                'id' => 2,
                'nama' => 'Fasilitas Umum'
            ]
        ];

        DB::table('aset_m_jenis')->insert($asetMJenisData);
    }
}
