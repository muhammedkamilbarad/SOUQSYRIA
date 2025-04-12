<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            ColorSeeder::class,
            // CitySeeder::class,
            // TransmissionTypeSeeder::class,
            // FuelTypeSeeder::class,
            PopularQuestionSeeder::class,
            CategorySeeder::class,
            VehicleBrandSeeder::class,
            VehicleModelSeeder::class,
            PackageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            SubscriptionRequestSeeder::class,
            // MarineTypeSeeder::class,
            FeatureGroupSeeder::class,
            FeatureSeeder::class,
            AdvertisementSeeder::class,
            // ArabicFeatureGroupSeeder::class,
            // ArabicFeaturesSeeder::class,
            // ArabicAdvertisementSeeder::class,
        ]);
    }
}
