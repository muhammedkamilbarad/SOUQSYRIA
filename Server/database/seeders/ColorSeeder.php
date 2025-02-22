<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;
use Illuminate\Support\Facades\Log;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Red'],
            ['name' => 'Blue'],
            ['name' => 'Green'],
            ['name' => 'Yellow'],
            ['name' => 'Purple'],
            ['name' => 'Orange'],
            ['name' => 'Pink'],
            ['name' => 'Brown'],
            ['name' => 'Black'],
            ['name' => 'White'],
        ];

        // Log the start of the seeding process
        Log::info('Starting ColorSeeder');

        foreach ($colors as $colorData) {
            try {
                // Check if color exists
                $existingColor = Color::where('name', $colorData['name'])->first();

                if ($existingColor) {
                    Log::info("Color '{$colorData['name']}' already exists, updating...");
                    $existingColor->update($colorData);
                } else {
                    Log::info("Creating new color: '{$colorData['name']}'");
                    Color::create($colorData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed color '{$colorData['name']}': " . $e->getMessage());
                // Remove the throw if you want to continue despite errors
                throw $e;
            }
        }

        Log::info('ColorSeeder completed');
    }
}