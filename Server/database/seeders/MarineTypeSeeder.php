<?php

namespace Database\Seeders;

use App\Models\MarineType;
use Illuminate\Database\Seeder;

class MarineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marineTypes = [
            ['name' => 'Cargo Ship'],
            ['name' => 'Container Ship'],
            ['name' => 'Tanker'],
            ['name' => 'Bulk Carrier'],
            ['name' => 'Passenger Ship'],
            ['name' => 'Fishing Vessel'],
            ['name' => 'Tugboat'],
            ['name' => 'Ferry'],
            ['name' => 'Yacht'],
            ['name' => 'Research Vessel']
        ];

        foreach ($marineTypes as $marineType) {
            MarineType::updateOrCreate(
                ['name' => $marineType['name']], 
                $marineType                       
            );
        }
    }
}