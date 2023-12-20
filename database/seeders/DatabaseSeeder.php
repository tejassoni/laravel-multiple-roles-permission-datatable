<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        $this->call([
            PermissionTableSeeder::class,
            RoleSeeder::class,
            CreateSuperAdminUserSeeder::class,
            CreateAdminUserSeeder::class,
            CreateMemberUserSeeder::class,
        ]);
    }
}
