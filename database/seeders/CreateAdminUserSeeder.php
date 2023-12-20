<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin user', 
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('admin@123456')
        ]);             

        $role = Role::findByName('Administrator');              
        $grantPermissions = [
            'role-list',
            'role-show', 
            'role-create', 
            'role-edit', 
            // 'role-delete', 
            'product-list', 
            'product-show', 
            'product-create', 
            'product-edit', 
            'product-delete',
            'order-list', 
            'order-show', 
            'order-create', 
            'order-edit', 
            // 'order-delete'  
         ]; 

        $permissions = Permission::whereIn('name',$grantPermissions)->pluck('id','id')->all();        
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
