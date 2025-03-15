<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use App\Models\LandAdvertisement;
use App\Models\HouseAdvertisement;
use App\Models\CarAdvertisement;
use App\Models\MarineAdvertisement;
use App\Models\MotorcycleAdvertisement;
use App\Models\VehicleAdvertisement;
use App\Enums\CoolingType; 
use App\Enums\MotorcycleType;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        // Land Advertisement (category_id: 1)
        $landAd = Advertisement::create([
            'title' => 'Prime Agricultural Land',
            'description' => 'Fertile land perfect for farming or investment',
            'price' => 50000.00,
            'currency' => 'USD',
            'city' => 'Damascus', // Assuming this is one of SyriaCities
            'location' => 'Rural Damascus Area',
            'category_id' => 1,
            'user_id' => 1, // Assuming user with ID 1 exists
            'ads_status' => 'accepted',
            'active_status' => 'active',
            'type' => 'sale',
        ]);

        LandAdvertisement::create([
            'advertisement_id' => $landAd->id,
            'square_meters' => 5000.00,
        ]);

        // House Advertisement (category_id: 2)
        $houseAd = Advertisement::create([
            'title' => 'Modern Family House',
            'description' => 'Spacious 3-bedroom house with garden',
            'price' => 150000.00,
            'currency' => 'USD',
            'city' => 'Aleppo',
            'location' => 'Central Aleppo',
            'category_id' => 2,
            'user_id' => 1,
            'ads_status' => 'accepted',
            'active_status' => 'active',
            'type' => 'sale',
        ]);

        HouseAdvertisement::create([
            'advertisement_id' => $houseAd->id,
            'number_of_rooms' => 3,
            'number_of_bathrooms' => 2,
            'building_age' => 5,
            'square_meters' => 200.00,
            'floor' => 1,
        ]);

        // Car Advertisement (category_id: 3)
        $carAd = Advertisement::create([
            'title' => 'Toyota Corolla 2020',
            'description' => 'Well-maintained sedan with low mileage',
            'price' => 20000.00,
            'currency' => 'USD',
            'city' => 'Homs',
            'location' => 'Homs Downtown',
            'category_id' => 3,
            'user_id' => 1,
            'ads_status' => 'accepted',
            'active_status' => 'active',
            'type' => 'sale',
        ]);

        $vehicleAd = VehicleAdvertisement::create([
            'advertisement_id' => $carAd->id,
            'color' => 'Silver',
            'mileage' => 35000,
            'year' => 2020,
            'brand_id' => 1,
            'model_id' => 1,
            'transmission_type' => 'Automatic',
            'fuel_type' => 'petrol', // Changed from 'Gasoline' to 'petrol'
            'horsepower' => 132,
            'cylinders' => 4,
            'engine_capacity' => 1.8,
            'condition' => 'USED',
        ]);

        CarAdvertisement::create([
            'advertisement_id' => $carAd->id,
            'seats' => 5,
            'doors' => 4,
            'seats_color' => 'Black',
        ]);

        // Motorcycle Advertisement (category_id: 5)
        $motoAd = Advertisement::create([
            'title' => 'Honda CB500F',
            'description' => 'Powerful motorcycle in excellent condition',
            'price' => 6000.00,
            'currency' => 'USD',
            'city' => 'Damascus',
            'location' => 'Damascus Suburbs',
            'category_id' => 5,
            'user_id' => 1,
            'ads_status' => 'accepted',
            'active_status' => 'active',
            'type' => 'sale',
        ]);

        $motoVehicleAd = VehicleAdvertisement::create([
            'advertisement_id' => $motoAd->id,
            'color' => 'Red',
            'mileage' => 12000,
            'year' => 2019,
            'brand_id' => 2,
            'model_id' => 2,
            'transmission_type' => 'Manual',
            'fuel_type' => 'petrol',
            'horsepower' => 47,
            'cylinders' => 2,
            'engine_capacity' => 0.5,
            'condition' => 'USED',
        ]);

        MotorcycleAdvertisement::create([
            'advertisement_id' => $motoAd->id,
            'cooling_type' => CoolingType::LIQUID_COOLED->name,
            'motorcycle_type' => MotorcycleType::CHOPPER_CRUISER->name,
        ]);
    }
}