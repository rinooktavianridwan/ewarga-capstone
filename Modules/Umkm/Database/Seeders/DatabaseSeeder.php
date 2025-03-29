<?php

namespace Modules\Umkm\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UmkmMasterSeeder::class,
            UmkmSeeder::class,
        ]);
    }
}
