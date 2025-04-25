<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $features = [
            // Land Features (feature_group_id: 1)
            ['name' => 'Near Highway', 'feature_group_id' => 1],
            ['name' => 'Fenced', 'feature_group_id' => 1],
            // Location Features (feature_group_id: 2)
            ['name' => 'Near School', 'feature_group_id' => 2],
            ['name' => 'Near Hospital', 'feature_group_id' => 2],
            
            // House Amenities (feature_group_id: 3)
            ['name' => 'Swimming Pool', 'feature_group_id' => 3],
            ['name' => 'Garage', 'feature_group_id' => 3],
            // Building Features (feature_group_id: 4)
            ['name' => 'Central Heating', 'feature_group_id' => 4],
            ['name' => 'Smart Home', 'feature_group_id' => 4],
            
            // Car Features (feature_group_id: 5)
            ['name' => 'Sunroof', 'feature_group_id' => 5],
            ['name' => 'Leather Seats', 'feature_group_id' => 5],
            // Safety Features (feature_group_id: 6)
            ['name' => 'ABS', 'feature_group_id' => 6],
            ['name' => 'Airbags', 'feature_group_id' => 6],
            
            // Marine Equipment (feature_group_id: 7)
            ['name' => 'Fishing Gear', 'feature_group_id' => 7],
            ['name' => 'Kitchen', 'feature_group_id' => 7],
            // Navigation Features (feature_group_id: 8)
            ['name' => 'GPS', 'feature_group_id' => 8],
            ['name' => 'Radar', 'feature_group_id' => 8],
            
            // Motorcycle Features (feature_group_id: 9)
            ['name' => 'Custom Exhaust', 'feature_group_id' => 9],
            ['name' => 'Heated Grips', 'feature_group_id' => 9],
            // Performance Features (feature_group_id: 10)
            ['name' => 'ABS', 'feature_group_id' => 10],
            ['name' => 'Traction Control', 'feature_group_id' => 10],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}