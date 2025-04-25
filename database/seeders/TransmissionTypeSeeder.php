<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransmissionType;
use Illuminate\Support\Facades\Log;

class TransmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transmissionTypes = [
        ['name' => 'Manual'],
        ['name' => 'Automatic'],
        ['name' => 'Semi-Automatic'],
        ['name' => 'CVT'],
        ['name' => 'Dual-Clutch'],
    ];

        // Log the start of the seeding process
        Log::info('Starting TransmissionTypeSeeder');

        foreach ($transmissionTypes as $transmissionTypeData) {
            try {
                // Check if transmission type exists
                $existingTransmissionType = TransmissionType::where('name', $transmissionTypeData['name'])->first();

                if ($existingTransmissionType) {
                    Log::info("TransmissionType '{$transmissionTypeData['name']}' already exists, updating...");
                    $existingTransmissionType->update($transmissionTypeData);
                } else {
                    Log::info("Creating new transmission type: '{$transmissionTypeData['name']}'");
                    TransmissionType::create($transmissionTypeData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed transmission type '{$transmissionTypeData['name']}': " . $e->getMessage());
                // Remove the throw if you want to continue despite errors
                throw $e;
            }
        }

        Log::info('TransmissionTypeSeeder completed');
    }
}