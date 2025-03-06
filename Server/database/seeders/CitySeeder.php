<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\Log;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Aleppo'],
            ['name' => 'Damascus'],
            ['name' => 'Homs'],
            ['name' => 'Latakia'],
            ['name' => 'Tartus'],
            ['name' => 'Daraa'],
            ['name' => 'Hasakah'],
            ['name' => 'Deir ez-Zor'],
            ['name' => 'Raqqa'],
            ['name' => 'Idlib'],
            ['name' => 'Qamishli'],
            ['name' => 'Suweida'],
            ['name' => 'Zabadani'],
            ['name' => 'Yarmouk'],
        ];

        // Log the start of the seeding process
        Log::info('Starting CitySeeder');

        foreach ($cities as $cityData) {
            try {
                // Check if city exists
                $existingCity = City::where('name', $cityData['name'])->first();

                if ($existingCity) {
                    Log::info("City '{$cityData['name']}' already exists, updating...");
                    $existingCity->update($cityData);
                } else {
                    Log::info("Creating new city: '{$cityData['name']}'");
                    City::create($cityData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed city '{$cityData['name']}': " . $e->getMessage());
                throw $e; // Remove this line if you want to continue despite errors
            }
        }

        Log::info('CitySeeder completed');
    }
}
