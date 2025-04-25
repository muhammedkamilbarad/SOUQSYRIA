<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FuelType;
use Illuminate\Support\Facades\Log;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuelTypes = [
            ['name' => 'Petrol'],
            ['name' => 'Diesel'],
            ['name' => 'Electric'],
            ['name' => 'Hybrid'],
            ['name' => 'CNG'],
            ['name' => 'LPG'],
        ];

        // Log the start of the seeding process
        Log::info('Starting FuelTypeSeeder');

        foreach ($fuelTypes as $fuelTypeData) {
            try {
                // Check if fuel type exists
                $existingFuelType = FuelType::where('name', $fuelTypeData['name'])->first();

                if ($existingFuelType) {
                    Log::info("FuelType '{$fuelTypeData['name']}' already exists, updating...");
                    $existingFuelType->update($fuelTypeData);
                } else {
                    Log::info("Creating new fuel type: '{$fuelTypeData['name']}'");
                    FuelType::create($fuelTypeData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed fuel type '{$fuelTypeData['name']}': " . $e->getMessage());
                // Remove the throw if you want to continue despite errors
                throw $e;
            }
        }

        Log::info('FuelTypeSeeder completed');
    }
}