<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $user = User::create([
            'name' => 'SuperAdmin user',
            'email' => 'superadmin@yopmail.com',
            'password' => Hash::make('superadmin@123456')
        ]);
        $role = Role::findByName('SuperAdmin');
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}