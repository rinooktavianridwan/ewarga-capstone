<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Umkm\Database\Seeders\ProdukSeederTableSeeder;
use Modules\Umkm\Database\Seeders\UmkmSeederTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            DB::table('users')->insert([
                'id' => $i,
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('m_provinsi')->insert([
            ['id' => 1, 'nama' => 'Jawa Timur'],
            ['id' => 2, 'nama' => 'Jawa Tengah'],
        ]);

        DB::table('m_kota')->insert([
            ['id' => 1, 'provinsi_id' => 1, 'nama' => 'Surabaya'],
            ['id' => 2, 'provinsi_id' => 2, 'nama' => 'Semarang'],
        ]);

        DB::table('m_kecamatan')->insert([
            ['id' => 1, 'provinsi_id' => 1, 'kota_id' => 1, 'nama' => 'Wonokromo'],
            ['id' => 2, 'provinsi_id' => 2, 'kota_id' => 2, 'nama' => 'Tembalang'],
        ]);

        DB::table('m_kelurahan')->insert([
            ['id' => 1, 'provinsi_id' => 1, 'kota_id' => 1, 'kecamatan_id' => 1, 'nama' => 'Darmo'],
            ['id' => 2, 'provinsi_id' => 2, 'kota_id' => 2, 'kecamatan_id' => 2, 'nama' => 'Bulusan'],
        ]);

        DB::table('instansi')->insert([
            [
                'parent_id' => null,
                'nama' => 'Instansi Contoh',
                'provinsi_id' => 1,
                'kota_id' => 1,
                'kecamatan_id' => 1,
                'kelurahan_id' => 1,
                'rw' => '01',
                'rt' => '02',
                'creator_id' => 1,
                'alamat' => 'Jl. Contoh No. 1',
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ],
        ]);

        DB::table('instansi')->insert([
            [
                'parent_id' => 1,
                'nama' => 'Instansi Contoh',
                'provinsi_id' => 1,
                'kota_id' => 1,
                'kecamatan_id' => 1,
                'kelurahan_id' => 1,
                'rw' => '01',
                'rt' => '02',
                'creator_id' => 1,
                'alamat' => 'Jl. Contoh No. 1',
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ],
        ]);

        DB::table('instansi')->insert([
            [
                'parent_id' => null,
                'nama' => 'Instansi Contoh',
                'provinsi_id' => 1,
                'kota_id' => 1,
                'kecamatan_id' => 1,
                'kelurahan_id' => 1,
                'rw' => '01',
                'rt' => '02',
                'creator_id' => 1,
                'alamat' => 'Jl. Contoh No. 1',
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ],
        ]);

        DB::table('instansi')->insert([
            [
                'parent_id' => 3,
                'nama' => 'Instansi Contoh',
                'provinsi_id' => 1,
                'kota_id' => 1,
                'kecamatan_id' => 1,
                'kelurahan_id' => 1,
                'rw' => '01',
                'rt' => '02',
                'creator_id' => 1,
                'alamat' => 'Jl. Contoh No. 1',
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ],
        ]);

        for ($i = 1; $i <= 1; $i++) {
            DB::table('warga')->insert([
                'instansi_id' => null,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);

            $id = DB::table('warga')->insertGetId([
                'instansi_id' => 1,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);

            $id = DB::table('warga')->insertGetId([
                'instansi_id' => 2,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);

            DB::table('warga_instansi')->insert([
                'warga_id' => $id,
                'user_id' => $i,
                'instansi_id' => 1,
                'alamat' => 'Alamat Instansi Warga ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 2; $i <= 2; $i++) {
            DB::table('warga')->insert([
                'instansi_id' => null,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);

            $id = DB::table('warga')->insertGetId([
                'instansi_id' => 3,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);

            $id = DB::table('warga')->insertGetId([
                'instansi_id' => 4,
                'user_id' => $i,
                'nama' => 'Warga ' . $i,
                'nomor_induk' => 'NI' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => str_pad($i, 16, '0', STR_PAD_LEFT),
                'no_kk' => str_pad($i + 1000, 16, '0', STR_PAD_LEFT),
                'no_tlp' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $i,
                'tgl_lahir' => now()->subYears(rand(18, 60))->toDateString(),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Alamat Warga ' . $i,
                'email' => 'warga' . $i . '@example.com',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => false,
            ]);
        }



        $this->call([
            UmkmSeederTableSeeder::class,
            ProdukSeederTableSeeder::class
        ]);

    }
}
