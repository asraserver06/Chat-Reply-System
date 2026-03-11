<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = ['send messages', 'view messages', 'manage users'];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles if they don't exist
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Assign permissions
        $userRole->syncPermissions(['send messages', 'view messages']); // user permissions
        $adminRole->syncPermissions(Permission::all()); // admin gets all permissions
    }
}
