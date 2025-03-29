<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UmkmSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Ambil warga & instansi
        $warga = DB::table('warga')->first();
        $instansi = DB::table('instansi')->first();
        $instansiId = $instansi?->id ?? null;

        if (!$warga) {
            $wargaId = DB::table('warga')->insertGetId([
                'nama' => 'Ridha Warga Dummy',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $wargaId = $warga->id;
        }

        // Ambil data bentuk usaha pertama
        $bentukId = DB::table('umkm_M_bentuk')->first()->id;
        // Ambil data jenis usaha pertama
        $jenisId = DB::table('umkm_M_jenis')->first()->id;

        // Validasi agar tidak error kalau kosong
        if (!$bentukId || !$jenisId) {
            $this->command->warn('⚠️ Tidak ada data bentuk/jenis usaha di tabel master. Seeder dibatalkan.');
            return;
        }

        // Tambah 1 UMKM
        $umkmId = DB::table('umkm')->insertGetId([
            'instansi_id' => $instansiId,
            'umkm_M_bentuk_id' => $bentukId,
            'umkm_M_jenis_id' => $jenisId,
            'nama' => 'UMKM Roti Bakar 88',
            'keterangan' => 'UMKM legendaris sejak 1945. Spesialis roti bakar isi tebal.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Produk
        DB::table('umkm_produk')->insert([
            [
                'instansi_id' => $instansiId,
                'umkm_id' => $umkmId,
                'nama' => 'Roti Coklat Keju',
                'keterangan' => 'Roti isi coklat & keju lumer',
                'harga' => 15000,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // Kontak
        DB::table('umkm_kontak')->insert([
            ['umkm_id' => $umkmId, 'kontak' => '081234567890', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Alamat
        $latitude = -7.966;
        $longitude = 112.615;
        DB::table('umkm_alamat')->insert([
            [
                'umkm_id' => $umkmId,
                'alamat' => DB::raw("ST_GeomFromText('POINT($longitude $latitude)')"),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // Foto
        DB::table('umkm_foto')->insert([
            ['umkm_id' => $umkmId, 'nama' => 'foto1.jpg', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Relasi warga ke UMKM
        DB::table('umkm_warga')->insert([
            ['umkm_id' => $umkmId, 'warga_id' => $wargaId, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
