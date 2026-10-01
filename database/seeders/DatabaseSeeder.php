<?php

namespace Database\Seeders;

use Database\Seeders\Seeds\PermissionsSeeder;
use Database\Seeders\Seeds\RolesSeeder;
use Database\Seeders\Seeds\UsersSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class
        ]);
    }
}
