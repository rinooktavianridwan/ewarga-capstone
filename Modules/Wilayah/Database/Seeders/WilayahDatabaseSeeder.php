<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;

class WilayahDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AsetMJenisSeeder::class);
        $this->call(AsetMStatusSeeder::class);
        // $this->call(AsetSeeder::class);
        // $this->call(AsetPenghuniSeeder::class);
    }
}
