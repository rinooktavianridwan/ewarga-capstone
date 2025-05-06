<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AsetSeeder extends Seeder
{
    public function run()
    {
        $asetData = [
            [
                'id' => 1,
                'warga_id' => 2,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 1,
                'nama' => 'Rumah Pak Johny',
                'alamat' => 'Jl. Mertojoyo No. 23, RT 15, RW 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.60361382712051 -7.944689503734679)')"),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 2,
                'warga_id' => 4,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 1,
                'nama' => 'Rumah Bu Siti',
                'alamat' => 'Jl. Mertojoyo No. 11, RT 15, RT 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.6023300015707 -7.944033233173902)')"),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 3,
                'warga_id' => 8,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 2,
                'nama' => 'Taman Merjosari',
                'alamat' => 'Jl. Mertojoyo Selatan No. 15, RT 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.6039211320968 -7.944767029702089)')"),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 4,
                'warga_id' => 12,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 1,
                'nama' => 'Kos Pak Supeno',
                'alamat' => 'Jl. Mertojoyo No. 07, RT 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.60346421014522 -7.943403190792656)')"),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'id' => 5,
                'warga_id' => 8,
                'instansi_id' => 1,
                'aset_m_jenis_id' => 2,
                'nama' => 'Musholla Al-Muhajirin',
                'alamat' => 'Jl. Mertojoyo Selatan No. 10, RT 05, RW 03, Kelurahan Merjosari, Kecamatan Lowokwaru, Kota Malang',
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.60395652495028 -7.944015476049777)')"),
                'created_at' => Carbon::now()->subMonth(),
            ],
        ];

        DB::table('aset')->insert($asetData);
    }
}
