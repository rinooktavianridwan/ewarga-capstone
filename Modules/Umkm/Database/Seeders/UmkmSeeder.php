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

        $bentukId = DB::table('umkm_m_bentuk')->first()->id;
        $jenisId = DB::table('umkm_m_jenis')->first()->id;
        $kontakTypeId = DB::table('umkm_m_kontak')->where('nama', 'WhatsApp')->first()->id;

        if (!$bentukId || !$jenisId || !$kontakTypeId) {
            $this->command->warn('⚠️ Tidak ada data bentuk/jenis usaha di tabel master. Seeder dibatalkan.');
            return;
        }

        $umkmId = DB::table('umkm')->insertGetId([
            'instansi_id' => $instansiId,
            'umkm_m_bentuk_id' => $bentukId,
            'umkm_m_jenis_id' => $jenisId,
            'nama' => 'UMKM Roti Bakar 88',
            'alamat' => 'Jl. Mawar No. 123',
            'keterangan' => 'UMKM legendaris sejak 1945. Spesialis roti bakar isi tebal.',
            'lokasi' => DB::raw("ST_GeomFromText('POINT(112.615 -7.966)')"),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kontakTypes = DB::table('umkm_m_kontak')->pluck('id', 'nama')->toArray();

        DB::table('umkm_kontak')->insert([
            [
                'umkm_id' => $umkmId,
                'umkm_m_kontak_id' => $kontakTypes['WhatsApp'],
                'kontak' => '081234567890',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'umkm_id' => $umkmId,
                'umkm_m_kontak_id' => $kontakTypes['Instagram'],
                'kontak' => '@rotibakar88',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'umkm_id' => $umkmId,
                'umkm_m_kontak_id' => $kontakTypes['Email'],
                'kontak' => 'rotibakar88@email.com',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);


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

        DB::table('umkm_foto')->insert([
            ['umkm_id' => $umkmId, 'nama' => 'foto1.jpg', 'file_path' => 'umkm_foto/foto1.jpg', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('umkm_warga')->insert([
            ['umkm_id' => $umkmId, 'warga_id' => $wargaId, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
