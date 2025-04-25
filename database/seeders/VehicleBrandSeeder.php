<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleBrand;
use Illuminate\Support\Facades\Log;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleBrands = [
            ['name' => 'Toyota', 'category_id' => 3],
            ['name' => 'Ford', 'category_id' => 3],
            ['name' => 'Honda', 'category_id' => 3],
            ['name' => 'BMW', 'category_id' => 3],
            ['name' => 'Mercedes-Benz', 'category_id' => 3],
            ['name' => 'Audi', 'category_id' => 3],
            ['name' => 'Hyundai', 'category_id' => 3],
            ['name' => 'Kia', 'category_id' => 3],
            ['name' => 'Porsche', 'category_id' => 3],
            ['name' => 'Tesla', 'category_id' => 3],
            ['name' => 'Yamaha', 'category_id' => 4],
            ['name' => 'Ducati', 'category_id' => 4],
        ];

        // Log the start of the seeding process
        Log::info('Starting VehicleBrandSeeder');

        foreach ($vehicleBrands as $vehicleBrandData) {
            try {
                // Check if vehicle brand exists
                $existingVehicleBrand = VehicleBrand::where('name', $vehicleBrandData['name'])->first();

                if ($existingVehicleBrand) {
                    Log::info("VehicleBrand '{$vehicleBrandData['name']}' already exists, updating...");
                    $existingVehicleBrand->update($vehicleBrandData);
                } else {
                    Log::info("Creating new vehicle brand: '{$vehicleBrandData['name']}'");
                    VehicleBrand::create($vehicleBrandData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed vehicle brand '{$vehicleBrandData['name']}': " . $e->getMessage());
                // Remove the throw if you want to continue despite errors
                throw $e;
            }
        }

        Log::info('VehicleBrandSeeder completed');
    }
}