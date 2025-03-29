<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UmkmMasterSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('umkm_M_bentuk')->insert([
            ['nama' => 'PT', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'CV', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'UMKM Mandiri', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('umkm_M_jenis')->insert([
            ['nama' => 'Online', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Offline', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Hybrid', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
