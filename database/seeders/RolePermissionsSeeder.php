<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Media;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Define Permissions
        $adminPermissions = ['category.create', 'category.delete', 'category.update', 'category.index',
                            'activities.index', 'metrix.index'];

        $userPermissions = ['post.create', 'post.delete', 'post.update', 'post.index',
                            'comment.create', 'comment.update', 'comment.delete', 'search'];

        // Create Permissions
        $allPermissions = array_unique(array_merge($adminPermissions, $userPermissions));

        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web' ]);
        }

        // Assign Permissions to Roles
        $adminRole->syncPermissions($adminPermissions); // Admin gets all admin permissions
        $userRole->syncPermissions($userPermissions);   // User gets all user permissions



        // Create Users and Assign Roles
        // Admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);




        // Regular user
        $user = User::factory()->create([
            'name' => 'Zaid',
            'email' => 'zaid@user.com',
            'password' => bcrypt('password'),


        ]);
        $user->assignRole($userRole);




    }
}
