<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahJenisAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Data dummy jenis aset
        $wilayahJenisAsetData = [
            [
                'id' => 1,
                'nama' => 'Aset Pribadi'
                ], 
                [
                'id' => 2,
                'nama' => 'Fasilitas Umum'
                ]
        ];

        DB::table('aset_m_jenis')->insert($wilayahJenisAsetData);
    }
}