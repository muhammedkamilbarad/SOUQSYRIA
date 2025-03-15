<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureGroup;

class FeatureGroupSeeder extends Seeder
{
    public function run()
    {
        $featureGroups = [
            // Land (category_id: 1)
            ['name' => 'Land Features', 'category_id' => 1],
            ['name' => 'Location Features', 'category_id' => 1],
            
            // House (category_id: 2)
            ['name' => 'House Amenities', 'category_id' => 2],
            ['name' => 'Building Features', 'category_id' => 2],
            
            // Car (category_id: 3)
            ['name' => 'Car Features', 'category_id' => 3],
            ['name' => 'Safety Features', 'category_id' => 3],
            
            // Marine (category_id: 4)
            ['name' => 'Marine Equipment', 'category_id' => 4],
            ['name' => 'Navigation Features', 'category_id' => 4],
            
            // Motorcycle (category_id: 5)
            ['name' => 'Motorcycle Features', 'category_id' => 5],
            ['name' => 'Performance Features', 'category_id' => 5],
        ];

        foreach ($featureGroups as $group) {
            FeatureGroup::create($group);
        }
    }
}