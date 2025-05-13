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
        $instansi = DB::table('instansi')->first();
        $instansiId = $instansi?->id;
        $wargaIds = DB::table('warga')->pluck('id')->take(5);

        if ($wargaIds->isEmpty()) {
            foreach (range(1, 5) as $i) {
                $id = DB::table('warga')->insertGetId([
                    'nama' => "Warga Dummy $i",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $wargaIds->push($id);
            }
        }

        $bentukId = DB::table('umkm_m_bentuk')->first()->id ?? null;
        $jenisId = DB::table('umkm_m_jenis')->first()->id ?? null;
        $kontakTypes = DB::table('umkm_m_kontak')->pluck('id', 'nama')->toArray();

        if (!$bentukId || !$jenisId || empty($kontakTypes)) {
            $this->command->warn('⚠️ Data master UMKM belum tersedia. Seeder dibatalkan.');
            return;
        }

        $umkmData = [];
        foreach (range(1, 5) as $i) {
            $umkmData[] = [
                'instansi_id' => $instansiId,
                'umkm_m_bentuk_id' => $bentukId,
                'umkm_m_jenis_id' => $jenisId,
                'nama' => "UMKM Contoh $i",
                'alamat' => "Jl. Contoh No. $i",
                'keterangan' => "UMKM ke-$i untuk keperluan seeding.",
                'lokasi' => DB::raw("ST_GeomFromText('POINT(112.61$i -7.96$i)')"),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('umkm')->insert($umkmData);

        $umkmIds = DB::table('umkm')->orderByDesc('id')->take(5)->pluck('id');

        $kontakData = [];
        $fotoData = [];
        $wargaData = [];
        foreach ($umkmIds as $index => $umkmId) {
            $kontakData[] = [
                'umkm_id' => $umkmId,
                'umkm_m_kontak_id' => $kontakTypes['WhatsApp'],
                'kontak' => "08123456$index",
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $kontakData[] = [
                'umkm_id' => $umkmId,
                'umkm_m_kontak_id' => $kontakTypes['Instagram'],
                'kontak' => "@umkmcontoh$index",
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $fotoData[] = [
                'umkm_id' => $umkmId,
                'nama' => "foto$index.jpg",
                'file_path' => "umkm_foto/foto$index.jpg",
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $wargaData[] = [
                'umkm_id' => $umkmId,
                'warga_id' => $wargaIds[$index],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('umkm_kontak')->insert($kontakData);
        DB::table('umkm_foto')->insert($fotoData);
        DB::table('umkm_warga')->insert($wargaData);
    }
}
