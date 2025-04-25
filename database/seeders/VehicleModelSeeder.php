<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleModel;
use App\Models\VehicleBrand;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define models with brand names instead of IDs
        $vehicleModels = [
            ['name' => 'Camry', 'brand_name' => 'Toyota'],
            ['name' => 'Corolla', 'brand_name' => 'Toyota'],
            ['name' => 'F-150', 'brand_name' => 'Ford'],
            ['name' => 'Mustang', 'brand_name' => 'Ford'],
            ['name' => 'Civic', 'brand_name' => 'Honda'],
            ['name' => 'Accord', 'brand_name' => 'Honda'],
            ['name' => 'X5', 'brand_name' => 'BMW'],
            ['name' => '3 Series', 'brand_name' => 'BMW'],
            ['name' => 'C-Class', 'brand_name' => 'Mercedes-Benz'],
            ['name' => 'E-Class', 'brand_name' => 'Mercedes-Benz'],
            ['name' => 'A4', 'brand_name' => 'Audi'],
            ['name' => 'Q5', 'brand_name' => 'Audi'],
            ['name' => 'Elantra', 'brand_name' => 'Hyundai'],
            ['name' => 'Tucson', 'brand_name' => 'Hyundai'],
            ['name' => 'Optima', 'brand_name' => 'Kia'],
            ['name' => 'Sorento', 'brand_name' => 'Kia'],
            ['name' => '911', 'brand_name' => 'Porsche'],
            ['name' => 'Cayenne', 'brand_name' => 'Porsche'],
            ['name' => 'Model 3', 'brand_name' => 'Tesla'],
            ['name' => 'Model Y', 'brand_name' => 'Tesla'],
            ['name' => 'R1', 'brand_name' => 'Yamaha'],
            ['name' => 'MT-07', 'brand_name' => 'Yamaha'],
            ['name' => 'Panigale', 'brand_name' => 'Ducati'],
            ['name' => 'Monster', 'brand_name' => 'Ducati'],
        ];

        Log::info('Starting VehicleModelSeeder');

        try {
            // Get all brands at once to avoid multiple queries
            $brands = VehicleBrand::all()->pluck('id', 'name')->toArray();
            
            // Prepare data with actual brand IDs
            $modelsToInsert = array_map(function ($model) use ($brands) {
                if (!isset($brands[$model['brand_name']])) {
                    Log::warning("Brand '{$model['brand_name']}' not found for model '{$model['name']}'");
                    return null;
                }
                return [
                    'name' => $model['name'],
                    'brand_id' => $brands[$model['brand_name']],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $vehicleModels);

            // Filter out null values from missing brands
            $modelsToInsert = array_filter($modelsToInsert);

            // Batch insert with upsert for efficiency
            DB::table('vehicle_models')->upsert(
                $modelsToInsert,
                ['name', 'vehicle_brand_id'], // Unique constraint columns
                ['name'] // Columns to update if duplicate
            );

            Log::info('Successfully seeded ' . count($modelsToInsert) . ' vehicle models');
        } catch (\Exception $e) {
            Log::error('VehicleModelSeeder failed: ' . $e->getMessage());
            throw $e;
        }

        Log::info('VehicleModelSeeder completed');
    }
}