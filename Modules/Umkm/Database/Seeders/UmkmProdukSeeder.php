<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UmkmProdukSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        for ($i = 0; $i < 5; $i++) {
            $umkmId = $i + 1;

            $produkAId = DB::table('umkm_produk')->insertGetId([
                'nama' => "Produk A UMKM $i",
                'keterangan' => "Deskripsi produk A untuk UMKM ke-$i",
                'harga' => 10000 + $i * 2000,
                'umkm_id' => $umkmId,
                'instansi_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $produkBId = DB::table('umkm_produk')->insertGetId([
                'nama' => "Produk B UMKM $i",
                'keterangan' => "Deskripsi produk B untuk UMKM ke-$i",
                'harga' => 12000 + $i * 2000,
                'umkm_id' => $umkmId,
                'instansi_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('umkm_produk_foto')->insert([
                [
                    'umkm_produk_id' => $produkAId,
                    'nama' => "Foto Produk A UMKM $i",
                    'file_path' => "produk_foto/produk_a_$i.jpg",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'umkm_produk_id' => $produkBId,
                    'nama' => "Foto Produk B UMKM $i",
                    'file_path' => "produk_foto/produk_b_$i.jpg",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}
