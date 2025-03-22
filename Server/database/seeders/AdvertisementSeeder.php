<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use App\Models\LandAdvertisement;
use App\Models\HouseAdvertisement;
use App\Models\VehicleAdvertisement;
use App\Models\CarAdvertisement;
use App\Models\MotorcycleAdvertisement;
use App\Models\MarineAdvertisement;
use App\Models\Image; 
use App\Models\Feature;
use App\Models\SaleDetail;

// Enums
use App\Enums\CategoryType;
use App\Enums\Colors;
use App\Enums\SyriaCities;
use App\Enums\TransmissionType;
use App\Enums\FuelType;
use App\Enums\CoolingType;
use App\Enums\MotorcycleType;
use App\Enums\MarineType;
use App\Enums\HouseType;
use App\Enums\CarType;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        /*
         |----------------------------------------------------------------------
         | 1) Configure how many ads for each category
         |----------------------------------------------------------------------
         */
        $landCount       = 1000;
        $houseCount      = 2500;
        $carCount        = 2500;
        $marineCount     = 1000;
        $motorcycleCount = 2000;

        /*
         |----------------------------------------------------------------------
         | 2) Image lists by category
         |----------------------------------------------------------------------
         | Adjust these to your actual image filenames/paths.
         */
        $imagesForLand = [
            'https://t3.ftcdn.net/jpg/03/62/18/34/360_F_362183460_4n0UlAKQ39ATMMkUxBEXmpLo1wQujTqd.jpg',
            'https://st4.depositphotos.com/3418487/39538/i/450/depositphotos_395380210-stock-photo-aerial-view-land-positioning-point.jpg',
            'https://www.ualberta.ca/en/alberta-land-institute/media-library/untitled1400350px3.jpg',
        ];
        $imagesForHouse = [
            'https://t3.ftcdn.net/jpg/01/18/46/52/360_F_118465200_0q7Of6UnbA8kDlYEe3a4PuIyue27fbuV.jpg',
            'https://t4.ftcdn.net/jpg/02/79/95/39/360_F_279953994_TmVqT7CQhWQJRLXev4oFmv8GIZTgJF1d.jpg',
            'https://static.vecteezy.com/system/resources/thumbnails/053/286/121/small/stunning-high-resolution-image-of-an-elegant-modern-duplex-design-photo.jpg'
        ];
        $imagesForCar = [
            'https://static.vecteezy.com/system/resources/thumbnails/026/992/343/small_2x/classic-modified-car-with-dark-smokie-background-ai-generative-free-photo.jpg',
            'https://www.autoshippers.co.uk/blog/wp-content/uploads/bugatti-centodieci.jpg',
            'https://colombiaone.com/wp-content/uploads/2024/11/from-rolls-royce-to-bugatti-dive-into-the-10-most-expensive-cars-in-the-world-redefining-opulence-in-2024-credit-calreyn88-cc0-1-0-696x442.jpg',
        ];
        $imagesForMarine = [
            'https://i.ytimg.com/vi/YhSiGpauCRA/maxresdefault.jpg',
            'https://www.carsworldwide.com/wp-content/uploads/2015/06/car-sea-freight1.jpg',
            'https://5.imimg.com/data5/SELLER/Default/2024/12/474167209/AY/JN/MS/236949370/compressjpeg-online-img-500x500.jpg',
        ];
        $imagesForMotorcycle = [
            'https://t4.ftcdn.net/jpg/02/34/21/83/360_F_234218337_sHgFOGQlhXxhrtH1oBPfbvs4cfUm6Mqp.jpg',
            'https://kickstart.bikeexif.com/wp-content/uploads/2025/03/selecting-aftermarket-exhaust-12-745x497.jpg',
            'https://www.royalenfield.com/content/dam/royal-enfield/motorcycles/new-classic-350/classic-350-motorcycle-listing.jpg',
        ];

        /*
         |----------------------------------------------------------------------
         | 3) Helper arrays for random picks
         |----------------------------------------------------------------------
         */
        $adsStatuses     = ['pending', 'accepted', 'rejected'];
        $activeStatuses  = ['active', 'inactive'];
        $types           = ['rent', 'sale'];
        $cities          = array_map(fn($city) => $city->name, SyriaCities::cases());
        $colors          = array_map(fn($c) => $c->name, Colors::cases());
        $transmissions   = array_map(fn($t) => $t->name, TransmissionType::cases());
        $fuelTypes       = array_map(fn($f) => $f->name, FuelType::cases());
        $coolingTypes    = array_map(fn($c) => $c->name, CoolingType::cases());
        $motorcycleTypes = array_map(fn($m) => $m->name, MotorcycleType::cases());
        $marineTypes     = array_map(fn($m) => $m->name, MarineType::cases());
        $houseTypes = array_map(fn($h) => $h->name, HouseType::cases());
        $carTypes = array_map(fn($c) => $c->name, CarType::cases());

        // Example IDs, must exist in your database
        $userIds   = [1, 2];
        $brandIds  = [1, 2];   // e.g. brand 1: Toyota, brand 2: Yamaha
        $modelIds  = [1, 2];   // e.g. model 1: Corolla, model 2: R6

        /*
         |----------------------------------------------------------------------
         | 4) LAND Ads
         |----------------------------------------------------------------------
         */
        for ($i = 0; $i < $landCount; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => "Land #$i",
                'description'  => 'Description for land advertisement.',
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(20000, 200000),
                'currency'     => 'USD',
                'location'     => 'Random location for land',
                'category_id'  => CategoryType::LAND->value, // 1
                'user_id'      => $userIds[array_rand($userIds)],
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
            ]);

            LandAdvertisement::create([
                'advertisement_id' => $ad->id,
                'square_meters'    => rand(100, 1000),
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }

            // Attach the list of land images to this advertisement
            $this->createCategoryImages($ad, $imagesForLand);
        }

        /*
         |----------------------------------------------------------------------
         | 5) HOUSE Ads
         |----------------------------------------------------------------------
         */
        for ($i = 0; $i < $houseCount; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => "House #$i",
                'description'  => 'Description for house advertisement.',
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(30000, 150000),
                'currency'     => 'USD',
                'location'     => 'Random location for house',
                'category_id'  => CategoryType::HOUSE->value, // 2
                'user_id'      => $userIds[array_rand($userIds)],
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
            ]);

            HouseAdvertisement::create([
                'advertisement_id'   => $ad->id,
                'number_of_rooms'    => rand(1, 5),
                'number_of_bathrooms'=> rand(1, 3),
                'building_age'       => rand(0, 20),
                'square_meters'      => rand(80, 300),
                'floor'              => rand(0, 5),
                'house_type' => $houseTypes[array_rand($houseTypes)],
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }

            // Attach the list of house images to this advertisement
            $this->createCategoryImages($ad, $imagesForHouse);

            // Attach features for House
            $this->attachCategoryFeatures($ad);
        }

        /*
         |----------------------------------------------------------------------
         | 6) CAR Ads
         |----------------------------------------------------------------------
         | We also create a VehicleAdvertisement + CarAdvertisement row.
         */
        for ($i = 0; $i < $carCount; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => "Car #$i",
                'description'  => 'Description for car advertisement.',
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(5000, 30000),
                'currency'     => 'USD',
                'location'     => 'Random location for car',
                'category_id'  => CategoryType::CAR->value, // 3
                'user_id'      => $userIds[array_rand($userIds)],
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
            ]);

            VehicleAdvertisement::create([
                'advertisement_id'  => $ad->id,
                'color'             => $colors[array_rand($colors)],
                'mileage'           => rand(5000, 200000),
                'year'              => rand(2000, 2023),
                'brand_id'          => $brandIds[array_rand($brandIds)],
                'model_id'          => $modelIds[array_rand($modelIds)],
                'transmission_type' => $transmissions[array_rand($transmissions)],
                'fuel_type'         => $fuelTypes[array_rand($fuelTypes)],
                'horsepower'        => rand(80, 300),
                'cylinders'         => rand(3, 8),
                'engine_capacity'   => rand(10, 30) / 10,
                'condition'         => ['NEW','USED'][array_rand(['NEW','USED'])],
            ]);

            CarAdvertisement::create([
                'advertisement_id' => $ad->id,
                'seats'            => rand(2, 7),
                'doors'            => rand(2, 5),
                'seats_color'      => $colors[array_rand($colors)],
                'car_type' => $carTypes[array_rand($carTypes)],
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }

            // Attach car images
            $this->createCategoryImages($ad, $imagesForCar);

            // Attach features for Car
            $this->attachCategoryFeatures($ad);
        }

        /*
         |----------------------------------------------------------------------
         | 7) MARINE Ads
         |----------------------------------------------------------------------
         */
        for ($i = 0; $i < $marineCount; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => "Marine #$i",
                'description'  => 'Description for marine advertisement.',
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(10000, 80000),
                'currency'     => 'USD',
                'location'     => 'Random location for marine',
                'category_id'  => CategoryType::MARINE->value, // 4
                'user_id'      => $userIds[array_rand($userIds)],
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
            ]);

            MarineAdvertisement::create([
                'advertisement_id' => $ad->id,
                'marine_type'      => $marineTypes[array_rand($marineTypes)],
                'length'           => rand(40, 100) / 10,
                'max_capacity'     => rand(4, 20),
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }

            // Attach marine images
            $this->createCategoryImages($ad, $imagesForMarine);

            // Attach features for Marine
            $this->attachCategoryFeatures($ad);
        }

        /*
         |----------------------------------------------------------------------
         | 8) MOTORCYCLE Ads
         |----------------------------------------------------------------------
         | Also creates a VehicleAdvertisement + MotorcycleAdvertisement row.
         */
        for ($i = 0; $i < $motorcycleCount; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => "Motorcycle #$i",
                'description'  => 'Description for motorcycle advertisement.',
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(1000, 10000),
                'currency'     => 'USD',
                'location'     => 'Random location for motorcycle',
                'category_id'  => CategoryType::MOTORCYCLE->value, // 5
                'user_id'      => $userIds[array_rand($userIds)],
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
            ]);

            VehicleAdvertisement::create([
                'advertisement_id'  => $ad->id,
                'color'             => $colors[array_rand($colors)],
                'mileage'           => rand(1000, 30000),
                'year'              => rand(2000, 2023),
                'brand_id'          => $brandIds[array_rand($brandIds)],
                'model_id'          => $modelIds[array_rand($modelIds)],
                'transmission_type' => $transmissions[array_rand($transmissions)],
                'fuel_type'         => $fuelTypes[array_rand($fuelTypes)],
                'horsepower'        => rand(20, 120),
                'cylinders'         => rand(1, 4),
                'engine_capacity'   => rand(5, 20) / 10,
                'condition'         => ['NEW','USED'][array_rand(['NEW','USED'])],
            ]);

            MotorcycleAdvertisement::create([
                'advertisement_id' => $ad->id,
                'cooling_type'     => $coolingTypes[array_rand($coolingTypes)],
                'motorcycle_type'  => $motorcycleTypes[array_rand($motorcycleTypes)],
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }

            // Attach motorcycle images
            $this->createCategoryImages($ad, $imagesForMotorcycle);

            // Attach features for Motorcycle
            $this->attachCategoryFeatures($ad);
        }
    }

    /**
     * Create images for the given ad using the provided list of image paths.
     * Adjust the code to store them exactly how you like (one or multiple).
     */
    protected function createCategoryImages(Advertisement $ad, array $imagePaths)
    {
        foreach ($imagePaths as $path) {
            Image::create([
                'advs_id'    => $ad->id,   // or whichever column references the ad
                'url' => $path,     // or 'file_name' / 'path' in your db
            ]);
        }
    }

    /**
     * Attach all features that belong to the ad's category (no randomness).
     */
    protected function attachCategoryFeatures(Advertisement $ad)
    {
        // Fetch all features whose featureGroup has category_id == $ad->category_id
        $compatibleFeatures = Feature::whereHas('featureGroup', function ($q) use ($ad) {
            $q->where('category_id', $ad->category_id);
        })->pluck('id');

        // If there are any, attach them all. (You can change sync() to attach() if you prefer)
        if ($compatibleFeatures->isNotEmpty()) {
            $ad->features()->sync($compatibleFeatures);
        }
    }

    /**
     * Create SaleDetail for an ad that has type 'sale'.
     * Example: 'is_swap' can be random or forced. We do random here.
     */
    protected function createSaleDetail(Advertisement $ad)
    {
        // Use your own logic to determine is_swap (true/false, etc.)
        // We'll randomly pick 0 or 1 as an integer or bool
        $randomIsSwap = (bool) rand(0, 1);

        SaleDetail::create([
            'advertisement_id' => $ad->id,
            'is_swap'          => $randomIsSwap,
        ]);
    }
}
