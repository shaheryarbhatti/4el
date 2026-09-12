<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Creates the three marketplace roles.
 * Add permissions here later if you need finer-grained control.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'vendor', 'customer'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
