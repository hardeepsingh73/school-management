<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeds:
     * - All application permissions
     * - Superadmin (all permissions), Admin (restricted), User (basic)
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // ----------------------
            // 1️⃣ Create Permissions
            // ----------------------
            $permissions = [
                // User management
                'create users', 'delete users', 'edit users', 'view users',

                // Role management
                'create roles', 'delete roles', 'edit roles', 'view roles',

                // Permission management
                'create permissions', 'delete permissions', 'edit permissions', 'view permissions',

                // Settings
                'create settings', 'delete settings', 'edit settings', 'view settings',

                // Logs
                'view activity logs', 'clear activity logs',
                'view error logs', 'clear error logs',
                'view login history', 'clear login history',

                // Basic
                'view dashboard',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // ----------------------
            // 2️⃣ Create Roles
            // ----------------------

            // Super Admin - all permissions
            $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
            $superadmin->syncPermissions(Permission::all());

            // Admin - restricted permissions
            $admin = Role::firstOrCreate(['name' => 'admin']);
            $admin->syncPermissions([
                'create users', 'delete users', 'edit users', 'view users',
                'view dashboard', 'view activity logs',
                'view error logs', 'view login history',
            ]);

            // Regular User - minimal permissions
            $user = Role::firstOrCreate(['name' => 'user']);
            $user->syncPermissions(['view dashboard']);
        });

        $this->command->info('✅ Roles and permissions seeded successfully.');
    }
}
