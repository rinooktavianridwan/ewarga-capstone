<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Data dummy aset
        $wilayahData = [
            [
                'id' => 1,
                'warga_id' => 1,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 1,
                'nama' => 'Kebun Sayur Pak Hambali',
                'alamat' => 'Jl. Mertojoyo No. 23, RT 15, RW 05, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => '-7.944689503734679, 112.60361382712051'
            ],
            [
                'id' => 2,
                'warga_id' => 2,
                'instansi_id' => 2,
                'aset_m_jenis_id' => 2,
                'nama' => 'Toko Madura Bu Herman',
                'alamat' => 'Jl. Mertojoyo No. 11, RT 15, RT 05, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => '-7.944033233173902, 112.6023300015707'
            ],
            [
                'id' => 3,
                'warga_id' => 3,
                'instansi_id' => 3,
                'aset_m_jenis_id' => 3,
                'nama' => 'Rumah Pak Eko',
                'alamat' => 'Jl. Mertojoyo Selatan No. 15, RT 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => '-7.944767029702089, 112.6039211320968'
            ],
            [
                'id' => 4,
                'warga_id' => 4,
                'instansi_id' => 4,
                'aset_m_jenis_id' => 4,
                'nama' => 'Rumah Pak Masyur',
                'alamat' => 'Jl. Joyo Utomo No. 23, RT 15, RW 05, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => '-7.943403190792656, 112.60346421014522'
            ],
            [
                'id' => 5,
                'warga_id' => 5,
                'instansi_id' => 5,
                'aset_m_jenis_id' => 5,
                'nama' => 'Nasi Pecel Bu Anggi',
                'alamat' => 'Jl. Mertojoyo Selatan No. 35, RT 15, RW 05, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => '-7.944015476049777, 112.60395652495028'
            ],
        ];

        DB::table('aset')->insert($wilayahData);
    }
}
