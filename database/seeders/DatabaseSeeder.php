<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Seeder::call(PermissionSeeder::class);
        Seeder::call(RoleSeeder::class);
        Seeder::call(AuthSeeder::class);
    }
}
