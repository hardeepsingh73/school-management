<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates default Admin and User accounts and assigns roles to them.
     */
    public function run(): void
    {
        // Ensure minimal roles exist before assigning
        $this->ensureRolesExist();

        // ------- SuperAdmin Account -------
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'SuperAdmin User',
                'password' => Hash::make('password123!'),
                'email_verified_at' => now(),
            ]
        );

        $superadmin->syncRoles('superadmin');

        // ------- Admin Account -------
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123!'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles('admin');

        // ------- Regular User -------
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password123!'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles('user');

        $this->command->info('✅ Default superadmin,admin and user accounts seeded successfully.');
    }

    /**
     * Ensure required roles exist before user creation.
     *
     * @return void
     */
    protected function ensureRolesExist(): void
    {
        $requiredRoles = ['admin', 'user'];

        foreach ($requiredRoles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
