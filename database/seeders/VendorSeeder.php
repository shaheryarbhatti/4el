<?php

namespace Database\Seeders;

use App\Models\TaxClass;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Creates a set of APPROVED vendor stores. Every demo product is assigned to
 * one of these vendors (see DemoProductSeeder), so each product has a real,
 * specific owner store.
 */
class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $defaultTax = optional(TaxClass::where('is_default', true)->first())->id;

        $vendors = [
            ['Tech Haven',      'techhaven',   'owner@techhaven.test',   'Ali Raza',      '+92 300 1112233', 'Blue Area, F-7 Markaz, Islamabad',            33.7156, 73.0551],
            ['Fashion Forward', 'fashionfwd',  'owner@fashionfwd.test',  'Sara Khan',     '+92 321 4445566', 'MM Alam Road, Gulberg III, Lahore',          31.5204, 74.3587],
            ['Home & Living Co','homeliving',  'owner@homeliving.test',  'Bilal Ahmed',   '+92 333 7778899', 'Tariq Road, Karachi',                        24.8607, 67.0011],
            ['Sports Arena',    'sportsarena', 'owner@sportsarena.test', 'Hassan Iqbal',  '+92 301 2223344', 'Saddar, Rawalpindi',                         33.5977, 73.0479],
            ['Gadget Galaxy',   'gadgetgalaxy','owner@gadgetgalaxy.test','Ayesha Malik',  '+92 345 5556677', 'Boulevard Mall, DHA Phase 6, Lahore',        31.4697, 74.4111],
        ];

        foreach ($vendors as [$store, $slug, $email, $owner, $phone, $address, $lat, $lng]) {
            // Owner user
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $owner, 'password' => 'password', 'phone' => $phone, 'status' => 'active']
            );
            $user->syncRoles(['vendor', 'customer']);

            // Approved store profile
            VendorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'store_name'            => $store,
                    'slug'                  => $slug,
                    'description'           => "Welcome to {$store} — quality products, fast shipping and great service.",
                    'phone'                 => $phone,
                    'address'               => $address,
                    'latitude'              => $lat,
                    'longitude'             => $lng,
                    'seller_type'           => 'business',
                    'status'                => 'approved',
                    'default_shipping_cost' => 0,
                    'default_free_shipping' => true,
                    'default_tax_class_id'  => $defaultTax,
                ]
            );
        }

        $this->command->info('Vendors seeded: '.VendorProfile::where('status', 'approved')->count().' approved stores.');
    }
}
