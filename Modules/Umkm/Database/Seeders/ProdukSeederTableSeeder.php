<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeederTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('produk')->insert([
            [
                'nama' => 'Macbook',
                'keterangan' => 'Laptop terbaik',
                'harga' => 25000000.00,
                'foto' => 'produk_foto/laptop_asus.jpg',
                'umkm_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sepeda Listrik Xtreme',
                'keterangan' => 'Sepeda listrik ramah lingkungan dengan daya 500W.',
                'harga' => 15000000.00,
                'foto' => 'produk_foto/sepeda_xtreme.jpg',
                'umkm_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('produk')->insert([
            [
                'nama' => 'Laptop ROG',
                'keterangan' => 'Laptop gaming terbaik dengan prosesor Ryzen 9.',
                'harga' => 25000000.00,
                'foto' => 'produk_foto/laptop_asus.jpg',
                'umkm_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sepeda BMX',
                'keterangan' => 'Sepeda listrik ramah lingkungan dengan daya 500W.',
                'harga' => 15000000.00,
                'foto' => 'produk_foto/sepeda_xtreme.jpg',
                'umkm_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('produk')->insert([
            [
                'nama' => 'Laptop Lenovo',
                'keterangan' => 'Laptop gaming terbaik dengan prosesor Ryzen 9.',
                'harga' => 25000000.00,
                'foto' => 'produk_foto/laptop_asus.jpg',
                'umkm_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sepeda Lipat',
                'keterangan' => 'Sepeda listrik ramah lingkungan dengan daya 500W.',
                'harga' => 15000000.00,
                'foto' => 'produk_foto/sepeda_xtreme.jpg',
                'umkm_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
