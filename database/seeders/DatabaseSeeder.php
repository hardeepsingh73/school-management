<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * This is the root seeder class for the application.
 * It is executed when running `php artisan db:seed`
 * and calls other seeder classes in the required order.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Here we can:
     * - Call additional seeders
     * - Optionally create test/demo data with factories
     *
     * @return void
     */
    public function run(): void
    {
        // Call individual seeders in the correct sequence
        $this->call([
            RolesAndPermissionsSeeder::class, // Sets up roles & permissions
            UsersSeeder::class,               // Creates default admin/user accounts
        ]);

        /**
         * Example: Quickly create a test user with a factory.
         * Uncomment if needed for development/demo.
         *
         * User::factory()->create([
         *     'name' => 'Test User',
         *     'email' => 'test@example.com',
         * ]);
         */
    }
}
