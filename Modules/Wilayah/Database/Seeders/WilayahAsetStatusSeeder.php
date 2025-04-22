<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahAsetStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wilayahStatusAsetData = [
            [
                'id' => 1,
                'nama' => 'Hambali Syarifuddin'
            ],
            [
                'id' => 2,
                'nama' => 'Bu Herman'
            ],
        ];

        DB::table('aset_m_status')->insert($wilayahStatusAsetData);
    }
}
