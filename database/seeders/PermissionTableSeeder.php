<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * KEY : MULTIPERMISSION
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Add new Modules permission here
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-show',
            'user-delete',
            'role-list',
            'role-create',
            'role-show',
            'role-edit',
            'role-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'category-show',
            'subcategory-list',
            'subcategory-create',
            'subcategory-edit',
            'subcategory-delete',
            'subcategory-show',
            'product-list',
            'product-create',
            'product-edit',
            'product-show',
            'product-delete',
            'order-list',
            'order-create',
            'order-edit',
            'order-delete',
            'order-show',

        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
