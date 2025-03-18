<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UmkmSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data dummy UMKM
        $umkmData = [
            [
                'nama' => 'Toko Sukses',
                'jenis_usaha' => 'offline',
                'kontak' => '088888',
                'warga_id' => 3,
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'foto' => 'umkm_foto/toko_sukses.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Warung Digital',
                'jenis_usaha' => 'online',
                'kontak' => '088888',
                'warga_id' => 6,
                'alamat' => 'Jl. Sudirman No. 5, Bandung',
                'foto' => 'umkm_foto/warung_digital.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Bengkel Motor Jaya',
                'jenis_usaha' => 'offline',
                'kontak' => '0888888',
                'warga_id' => 6,
                'alamat' => 'Jl. Diponegoro No. 12, Surabaya',
                'foto' => 'umkm_foto/bengkel_motor.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // Insert data ke tabel `umkm`
        DB::table('umkm')->insert($umkmData);
    }
}
