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
        //Data dummy foto aset
        $wilayahFotoData = [
            [
                'id' => 1,
                'aset_id' => 1,
                'nama' => 'Kebun Sayur Pak Hambali',
                'file_path' => 'aset_foto/1/1/2025-04-21_14-30-00/kebun_hambali.jpg'
            ],
            [
                'id' => 2,
                'aset_id' => 2,
                'nama' => 'Toko Madura Bu Herman',
                'file_path' => 'aset_foto/2/2/2025-03-31_10-32-00/toko_herman.jpg'
            ],
            [
                'id' => 3,
                'aset_id' => 3,
                'nama' => 'Rumah Pak Eko',
                'file_path' => 'aset_foto/3/3/2025-02-25_13-10-00/rumah_eko.jpg'
            ],
            [
                'id' => 4,
                'aset_id' => 4,
                'nama' => 'Rumah Pak Masyur',
                'file_path' => 'aset_foto/4/4/2025-01-03_16-43-00/rumah_masyur.jpg'
            ],
            [
                'id' => 5,
                'aset_id' => 5,
                'nama' => 'Nasi Pecel Bu Anggi',
                'file_path' => 'aset_foto/5/5/2024-10-20_09-29-00/pecel_anggi.jpg'
            ],
            [
                'id' => 6,
                'aset_id' => 6,
                'nama' => 'Rumah Bu Desi',
                'file_path' => 'aset_foto/6/6/2025-01-21_10-21-00/rumah_desi.jpg'
            ],
            [
                'id' => 7,
                'aset_id' => 7,
                'nama' => 'Gado-Gado Bu Celik',
                'file_path' => 'aset_foto/7/7/2025-04-01_16-00-00/gado_celik.jpg'
            ],
            [
                'id' => 8,
                'aset_id' => 8,
                'nama' => 'Rumah Pak Agung',
                'file_path' => 'aset_foto/8/8/2024-11-30_17-00-00/rumah_agung.jpg'
            ],
            [
                'id' => 9,
                'aset_id' => 9,
                'nama' => 'Sawah Pak Rusdi',
                'file_path' => 'aset_foto/9/9/2023-05-11_08-41-00/sawah_rusdi.jpg'
            ],
            [
                'id' => 10,
                'aset_id' => 10,
                'nama' => 'Warnet Topp',
                'file_path' => 'aset_foto/10/10/2024-04-30_21-44-00/warnet_topp.jpg'
            ],
        ];

        DB::table('aset_foto')->insert($wilayahFotoData);
    }  
}