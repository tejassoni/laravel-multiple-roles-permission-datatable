<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateMemberUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Member user', 
            'email' => 'member@yopmail.com',
            'password' => Hash::make('member@123456')
        ]);             

        $role = Role::findByName('Member');      
        $grantPermissions = [
            // 'role-list', 
            // 'role-create', 
            // 'role-edit', 
            // 'role-delete', 
            // 'product-list', 
            // 'product-create', 
            // 'product-edit', 
            // 'product-delete',
            'order-list', 
            'order-create', 
            // 'order-edit', 
            // 'order-delete'  
         ]; 

        $permissions = Permission::whereIn('name',$grantPermissions)->pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
