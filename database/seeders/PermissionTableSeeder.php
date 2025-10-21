<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin     = Role::firstOrCreate(['name' => 'super-admin']);
        $administrator  = Role::firstOrCreate(['name' => 'administrator']);
        $mentor         = Role::firstOrCreate(['name' => 'mentor']);
        $koordinator    = Role::firstOrCreate(['name' => 'koordinator']);
        $anggota        = Role::firstOrCreate(['name' => 'anggota']);

        $permissions = [

            ['id' => 1,  'name' => 'users-view'],
            ['id' => 2,  'name' => 'users-create'],
            ['id' => 3,  'name' => 'users-edit'],
            ['id' => 4,  'name' => 'users-delete'],
            ['id' => 5,  'name' => 'users-show'],

            ['id' => 6,  'name' => 'roles-list'],
            ['id' => 7,  'name' => 'roles-create'],
            ['id' => 8,  'name' => 'roles-edit'],
            ['id' => 9,  'name' => 'roles-delete'],

            ['id' => 10, 'name' => 'permission-view'],
            ['id' => 11, 'name' => 'permission-create'],
            ['id' => 12, 'name' => 'permission-edit'],
            ['id' => 13, 'name' => 'permission-delete'],

            ['id' => 14, 'name' => 'setting-general'],
            ['id' => 15, 'name' => 'setting-smtp',],

            ['id' => 16, 'name' => 'config-view'],
            ['id' => 17, 'name' => 'config-create'],
            ['id' => 18, 'name' => 'config-edit'],
            ['id' => 19, 'name' => 'config-delete'],

            //feedback
            ['id' => 20, 'name' => 'feedback-view'],
            ['id' => 21, 'name' => 'feedback-create'],
            ['id' => 22, 'name' => 'feedback-edit'],
            ['id' => 23, 'name' => 'feedback-delete'],

            //activity
            ['id' => 24, 'name' => 'activity-view'],
            ['id' => 25, 'name' => 'activity-create'],
            ['id' => 26, 'name' => 'activity-edit'],
            ['id' => 27, 'name' => 'activity-delete'],

            //version
            ['id' => 28, 'name' => 'version-view'],
            ['id' => 29, 'name' => 'version-create'],
            ['id' => 30, 'name' => 'version-edit'],
            ['id' => 31, 'name' => 'version-delete'],

            ['id' => 32, 'name' => 'setting-register'],

            ['id' => 33, 'name' => 'user-login-as'],

            //category
            ['id' => 34, 'name' => 'category-view'],
            ['id' => 35, 'name' => 'category-create'],
            ['id' => 36, 'name' => 'category-edit'],
            ['id' => 37, 'name' => 'category-delete'],
            
            // Blog
            ['id' => 38, 'name' => 'blog-view'],
            ['id' => 39, 'name' => 'blog-create'],
            ['id' => 40, 'name' => 'blog-edit'],
            ['id' => 41, 'name' => 'blog-delete'],

            // Tag
            ['id' => 42, 'name' => 'tag-view'],
            ['id' => 43, 'name' => 'tag-create'],
            ['id' => 44, 'name' => 'tag-edit'],
            ['id' => 45, 'name' => 'tag-delete'],

            // manage blog
            ['id' => 46, 'name' => 'manage-blog-view'],
            ['id' => 47, 'name' => 'manage-blog-create'],
            ['id' => 48, 'name' => 'manage-blog-edit'],
            ['id' => 49, 'name' => 'manage-blog-delete'],
        ];

        foreach ($permissions as $item) {
            Permission::firstOrCreate(['id' => $item['id']], ['name' => $item['name']]);
        }

        $superadmin->syncPermissions(Permission::all());
        $administrator->syncPermissions([1, 2, 3, 4, 5, 6, 7, 8, 9, 14, 15, 20, 21, 22, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49]);
        $mentor->syncPermissions([34, 35, 36, 37, 38, 39, 40, 41]);
        $koordinator->syncPermissions([34, 35, 36, 37, 38, 39, 40, 41]);
        $anggota->syncPermissions([38, 39, 40, 41]);
        
    }
}
