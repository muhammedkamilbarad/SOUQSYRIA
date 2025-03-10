<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\CarAdvertisement;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Feature;
use App\Models\FuelType;
use App\Models\HouseAdvertisement;
use App\Models\Image;
use App\Models\LandAdvertisement;
use App\Models\MarineAdvertisement;
use App\Models\MarineType;
use App\Models\MotorcycleAdvertisement;
use App\Models\TransmissionType;
use App\Models\User;
use App\Models\VehicleAdvertisement;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Enums\CategoryType;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Get existing records for relationships
        $users = User::all()->pluck('id')->toArray();
        $cities = City::all()->pluck('id')->toArray();
        $colors = Color::all()->pluck('id')->toArray();
        $fuelTypes = FuelType::all()->pluck('id')->toArray();
        $transmissions = TransmissionType::all()->pluck('id')->toArray();
        $marineTypes = MarineType::all()->pluck('id')->toArray();
        $features = Feature::all()->pluck('id')->toArray();
        
        // Get category IDs based on CategoryType enum
        $categories = Category::all();
        $carCategoryId = $categories->where('id', CategoryType::CAR->value)->first()->id;
        $motorcycleCategoryId = $categories->where('id', CategoryType::MOTORCYCLE->value)->first()->id;
        $marineCategoryId = $categories->where('id', CategoryType::MARINE->value)->first()->id;
        $houseCategoryId = $categories->where('id', CategoryType::HOUSE->value)->first()->id;
        $landCategoryId = $categories->where('id', CategoryType::LAND->value)->first()->id;
        
        // Define advertisement types
        $advertisementTypes = ['sale', 'rent'];
        
        // Define possible statuses
        $adsStatusOptions = ['pending', 'accepted', 'rejected'];
        $activeStatusOptions = ['active', 'inactive'];
        
        // Vehicle conditions
        $vehicleConditions = ['NEW', 'USED'];
        
        // Create Car Advertisements (10)
        $this->createCarAdvertisements($faker, $users, $cities, $carCategoryId, $colors, $fuelTypes, 
            $transmissions, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, 10);
            
        // Create Motorcycle Advertisements (10)
        $this->createMotorcycleAdvertisements($faker, $users, $cities, $motorcycleCategoryId, $colors, $fuelTypes, 
            $transmissions, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, 10);
            
        // Create Marine Advertisements (10)
        $this->createMarineAdvertisements($faker, $users, $cities, $marineCategoryId, $colors, $fuelTypes, 
            $transmissions, $marineTypes, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, 10);
            
        // Create House Advertisements (10)
        $this->createHouseAdvertisements($faker, $users, $cities, $houseCategoryId, $features, 
            $advertisementTypes, $adsStatusOptions, $activeStatusOptions, 10);
            
        // Create Land Advertisements (10)
        $this->createLandAdvertisements($faker, $users, $cities, $landCategoryId, $features, 
            $advertisementTypes, $adsStatusOptions, $activeStatusOptions, 10);
    }
    
    /**
     * Create Car Advertisements
     */
    private function createCarAdvertisements($faker, $users, $cities, $categoryId, $colors, $fuelTypes, 
        $transmissions, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            // Create base advertisement
            $advertisement = $this->createBaseAdvertisement(
                $faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions
            );
            
            // Create vehicle advertisement
            $vehicle = $this->createVehicleAdvertisement(
                $faker, $advertisement->id, $colors, $fuelTypes, $transmissions, $vehicleConditions
            );
            
            // Create car specific details
            CarAdvertisement::create([
                'advertisement_id' => $advertisement->id,
                'seats' => $faker->numberBetween(2, 7),
                'doors' => $faker->numberBetween(2, 5)
            ]);
            
            // Attach features
            $this->attachFeatures($advertisement, $features, $faker->numberBetween(2, 5));
            
            // Add images
            $this->addImages($advertisement, $faker, $faker->numberBetween(2, 5));
        }
    }
    
    /**
     * Create Motorcycle Advertisements
     */
    private function createMotorcycleAdvertisements($faker, $users, $cities, $categoryId, $colors, $fuelTypes, 
        $transmissions, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            // Create base advertisement
            $advertisement = $this->createBaseAdvertisement(
                $faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions
            );
            
            // Create vehicle advertisement
            $vehicle = $this->createVehicleAdvertisement(
                $faker, $advertisement->id, $colors, $fuelTypes, $transmissions, $vehicleConditions
            );
            
            // Create motorcycle specific details
            MotorcycleAdvertisement::create([
                'advertisement_id' => $advertisement->id,
                'cylinders' => $faker->numberBetween(1, 6)
            ]);
            
            // Attach features
            $this->attachFeatures($advertisement, $features, $faker->numberBetween(2, 5));
            
            // Add images
            $this->addImages($advertisement, $faker, $faker->numberBetween(2, 4));
        }
    }
    
    /**
     * Create Marine Advertisements
     */
    private function createMarineAdvertisements($faker, $users, $cities, $categoryId, $colors, $fuelTypes, 
        $transmissions, $marineTypes, $features, $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $vehicleConditions, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            // Create base advertisement
            $advertisement = $this->createBaseAdvertisement(
                $faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions
            );
            
            // Create vehicle advertisement
            $vehicle = $this->createVehicleAdvertisement(
                $faker, $advertisement->id, $colors, $fuelTypes, $transmissions, $vehicleConditions
            );
            
            // Create marine specific details
            MarineAdvertisement::create([
                'advertisement_id' => $advertisement->id,
                'type_id' => $faker->randomElement($marineTypes),
                'length' => $faker->randomFloat(2, 3, 30),
                'max_capacity' => $faker->numberBetween(2, 50)
            ]);
            
            // Attach features
            $this->attachFeatures($advertisement, $features, $faker->numberBetween(2, 5));
            
            // Add images
            $this->addImages($advertisement, $faker, $faker->numberBetween(2, 6));
        }
    }
    
    /**
     * Create House Advertisements
     */
    private function createHouseAdvertisements($faker, $users, $cities, $categoryId, $features, 
        $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            // Create base advertisement
            $advertisement = $this->createBaseAdvertisement(
                $faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions
            );
            
            // Create house specific details
            HouseAdvertisement::create([
                'advertisement_id' => $advertisement->id,
                'number_of_rooms' => $faker->numberBetween(1, 8),
                'building_age' => $faker->numberBetween(0, 100),
                'square_meters' => $faker->numberBetween(50, 500),
                'floor' => $faker->numberBetween(0, 20)
            ]);
            
            // Attach features
            $this->attachFeatures($advertisement, $features, $faker->numberBetween(3, 7));
            
            // Add images
            $this->addImages($advertisement, $faker, $faker->numberBetween(3, 8));
        }
    }
    
    /**
     * Create Land Advertisements
     */
    private function createLandAdvertisements($faker, $users, $cities, $categoryId, $features, 
        $advertisementTypes, $adsStatusOptions, $activeStatusOptions, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            // Create base advertisement
            $advertisement = $this->createBaseAdvertisement(
                $faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions
            );
            
            // Create land specific details
            LandAdvertisement::create([
                'advertisement_id' => $advertisement->id,
                'square_meters' => $faker->numberBetween(100, 10000)
            ]);
            
            // Attach features
            $this->attachFeatures($advertisement, $features, $faker->numberBetween(1, 4));
            
            // Add images
            $this->addImages($advertisement, $faker, $faker->numberBetween(2, 5));
        }
    }
    
    /**
     * Create a base advertisement
     */
    private function createBaseAdvertisement($faker, $users, $cities, $categoryId, $advertisementTypes, $adsStatusOptions, $activeStatusOptions)
    {
        return Advertisement::create([
            'title' => $faker->sentence(6, true),
            'description' => $faker->paragraphs(3, true),
            'price' => $faker->numberBetween(500, 500000),
            'city_id' => $faker->randomElement($cities),
            'location' => $faker->address,
            'category_id' => $categoryId,
            'user_id' => $faker->randomElement($users),
            'ads_status' => $faker->randomElement($adsStatusOptions),
            'active_status' => $faker->randomElement($activeStatusOptions),
            'type' => $faker->randomElement($advertisementTypes)
        ]);
    }
    
    /**
     * Create a vehicle advertisement
     */
    private function createVehicleAdvertisement($faker, $advertisementId, $colors, $fuelTypes, $transmissions, $conditions)
    {
        $brands = VehicleBrand::all()->pluck('id')->toArray();
        $models = VehicleModel::all()->pluck('id')->toArray();

        return VehicleAdvertisement::create([
            'advertisement_id' => $advertisementId,
            'color_id' => $faker->randomElement($colors),
            'mileage' => $faker->numberBetween(0, 200000),
            'year' => $faker->numberBetween(1990, 2023),
            'engine_capacity' => $faker->randomFloat(1, 1.0, 6.0),
            'brand_id' => $faker->randomElement($brands),
            'model_id' => $faker->randomElement($models),
            'fuel_type_id' => $faker->randomElement($fuelTypes),
            'horsepower' => $faker->numberBetween(50, 600),
            'transmission_id' => $faker->randomElement($transmissions),
            'condition' => $faker->randomElement($conditions)
        ]);
    }
    
    /**
     * Attach features to an advertisement
     */
    private function attachFeatures($advertisement, $features, $count)
    {
        $selectedFeatures = (array) array_rand(array_flip($features), min($count, count($features)));
        $advertisement->features()->attach($selectedFeatures);
    }
    
    /**
     * Add images to an advertisement
     */
    private function addImages($advertisement, $faker, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            Image::create([
                'advs_id' => $advertisement->id,
                'url' => $faker->imageUrl(800, 600, 'transport'),
                // 'order' => $i + 1
            ]);
        }
    }
}
