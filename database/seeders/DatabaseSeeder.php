<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->isProduction()) {
            $this->command?->warn('Refusing to seed demo data in production.');

            return;
        }

        $this->call(DemoSeeder::class);
    }
}
