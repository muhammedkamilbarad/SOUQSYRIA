<?php

namespace Database\Seeders;

use App\Models\FeatureGroup;
use Illuminate\Database\Seeder;

class FeatureGroupSeeder extends Seeder
{
    public function run()
    {
        $featureGroups = [
            ['name' => 'Vehicle Features', 'category_id' => 3],  // car
            ['name' => 'House Features', 'category_id' => 2],    // house
            ['name' => 'Land Features', 'category_id' => 1],     // land
            ['name' => 'Marine Features', 'category_id' => 4],   // marine
            ['name' => 'General Features', 'category_id' => 1]   // using land as default for general features
        ];
        
        foreach ($featureGroups as $group) {
            FeatureGroup::create($group);
        }
    }
}
