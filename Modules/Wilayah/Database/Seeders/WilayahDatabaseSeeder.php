<?php

namespace Modules\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WilayahAsetJenisSeeder::class);
        $this->call(WilayahAsetStatusSeeder::class);
        $this->call(WilayahAsetSeeder::class);
        $this->call(WilayahAsetPenghuniSeeder::class);
        $this->call(WilayahAsetFotoSeeder::class);
    }
}
