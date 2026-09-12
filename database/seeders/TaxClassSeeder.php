<?php

namespace Database\Seeders;

use App\Models\TaxClass;
use Illuminate\Database\Seeder;

/**
 * Default tax classes so products can be created immediately.
 */
class TaxClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'No Tax',   'rate' => 0,  'is_default' => true],
            ['name' => 'Standard', 'rate' => 15, 'is_default' => false],
            ['name' => 'Reduced',  'rate' => 5,  'is_default' => false],
        ];

        foreach ($classes as $c) {
            TaxClass::firstOrCreate(['name' => $c['name']], $c);
        }
    }
}
