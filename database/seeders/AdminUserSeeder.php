<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates the default admin login so you can enter the panel immediately.
 *
 *   Email    : admin@ebay.test
 *   Password : password
 *
 * ⚠ Change these credentials in production!
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@ebay.test'],
            [
                'name'     => 'Super Admin',
                'password' => 'password', // auto-hashed by the model cast
            ]
        );

        $admin->syncRoles('admin');
    }
}
