<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureGroup;

class ArabicFeatureGroupSeeder extends Seeder
{
    
    // Run the database seeds.
    public function run(): void
    {
        $featureGroups = [
            // Land (category_id: 1)
            ['name' => 'ميزات الأرض', 'category_id' => 1],
            ['name' => 'ميزات الموقع', 'category_id' => 1],
            
            // House (category_id: 2)
            ['name' => 'مرافق المنزل', 'category_id' => 2],
            ['name' => 'ميزات المبنى', 'category_id' => 2],
            
            // Car (category_id: 3)
            ['name' => 'ميزات السيارة', 'category_id' => 3],
            ['name' => 'ميزات الأمان', 'category_id' => 3],
            
            // Marine (category_id: 4)
            ['name' => 'معدات بحرية', 'category_id' => 4],
            ['name' => 'ميزات الملاحة', 'category_id' => 4],
            
            // Motorcycle (category_id: 5)
            ['name' => 'ميزات الدراجة النارية', 'category_id' => 5],
            ['name' => 'ميزات الأداء', 'category_id' => 5],
        ];

        foreach ($featureGroups as $group) {
            FeatureGroup::create($group);
        }
    }
}
