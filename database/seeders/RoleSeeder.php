<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $roles = [
            'SuperAdmin', 
            'Administrator',
            'Member' 
         ]; 
         foreach ($roles as $role) { 
            Role::create(['name' => $role]); 
         }
    }
}
