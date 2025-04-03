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
use App\Models\RentDetail;

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

class ArabicAdvertisementSeeder extends Seeder
{



    // Run the database seeds.
    public function run(): void
    {
        // Helper arrays for random picks
        // $adsStatuses     = ['pending', 'accepted', 'rejected'];
        $adsStatuses     = ['pending', 'rejected'];
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
        $brandIds = [1, 1, 4, 4, 3, 10];
        $modelIds = [1, 2, 7, 8, 6, 20];

        // Create Land Advertisements
        for ($i = 0; $i < 6; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            // $ad_status = $adsStatuses[1];
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => $this->landTitles(),
                'description'  => $this->landDescriptions(),
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(20000, 200000),
                'currency'     => 'USD',
                'location'     => $this->locations(),
                'category_id'  => CategoryType::LAND->value, // 1
                'user_id'      => rand(5, 50),
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
                'image_upload_status' => 'completed',
            ]);

            LandAdvertisement::create([
                'advertisement_id' => $ad->id,
                'square_meters'    => rand(1000, 10000),
            ]);

            // If the ad is for sale, create SaleDetail
            if ($adType === 'sale') {
                $this->createSaleDetail($ad);
            }
            
            // If the ad is for rent, create RentDetail
            if ($adType === 'rent') {
                $this->createRentDetail($ad);
            }

            // Attach the list of land images to this advertisement
            $this->createCategoryImages($ad, $this->landImages($i));

            // Attach features for Motorcycle
            $this->attachCategoryFeatures($ad);
        }

        // // // Create House Advertisements
        for ($i = 0; $i < 6; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            // $ad_status = $adsStatuses[1];
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => $this->houseTitles(),
                'description'  => $this->houseDescriptions(),
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(30000, 150000),
                'currency'     => 'USD',
                'location'     => $this->locations(),
                'category_id'  => CategoryType::HOUSE->value, // 2
                'user_id'      => rand(5, 50),
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
                'image_upload_status' => 'completed',
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

            // If the ad is for rent, create RentDetail
            if ($adType === 'rent') {
                $this->createRentDetail($ad);
            }

            // Attach the list of house images to this advertisement
            $this->createCategoryImages($ad, $this->houseImages($i));

            // Attach features for House
            $this->attachCategoryFeatures($ad);
        }
        
        // // Create Car Advertisements
        for ($i = 0; $i < 6; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            // $ad_status = $adsStatuses[1];
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => $this->carTitles($i),
                'description'  => $this->carDescriptions(),
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(5000, 30000),
                'currency'     => 'USD',
                'location'     => $this->locations(),
                'category_id'  => CategoryType::CAR->value, // 3
                'user_id'      => rand(5, 50),
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
                'image_upload_status' => 'completed',
            ]);

            VehicleAdvertisement::create([
                'advertisement_id'  => $ad->id,
                'color'             => $colors[array_rand($colors)],
                'mileage'           => rand(5000, 200000),
                'year'              => rand(2000, 2023),
                'brand_id'          => $brandIds[$i],
                'model_id'          => $modelIds[$i],
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

            // If the ad is for rent, create RentDetail
            if ($adType === 'rent') {
                $this->createRentDetail($ad);
            }

            // Attach the list of car images to this advertisement
            $this->createCategoryImages($ad, $this->carImages($i));

            // Attach features for Car
            $this->attachCategoryFeatures($ad);
        }

        // Create marine Advertisements
        for ($i = 0; $i < 6; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            // $ad_status = $adsStatuses[1];
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => $this->marineTitles(),
                'description'  => $this->marineDescriptions(),
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(10000, 80000),
                'currency'     => 'USD',
                'location'     => $this->locations(),
                'category_id'  => CategoryType::MARINE->value, // 4
                'user_id'      => rand(5, 50),
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
                'image_upload_status' => 'completed',
            ]);

            VehicleAdvertisement::create([
                'advertisement_id'  => $ad->id,
                'color'             => $colors[array_rand($colors)],
                'mileage'           => rand(5000, 200000),
                'year'              => rand(2000, 2023),
                'brand_id'          => $brandIds[$i],
                'model_id'          => $modelIds[$i],
                'transmission_type' => $transmissions[array_rand($transmissions)],
                'fuel_type'         => $fuelTypes[array_rand($fuelTypes)],
                'horsepower'        => rand(80, 300),
                'cylinders'         => rand(3, 8),
                'engine_capacity'   => rand(10, 30) / 10,
                'condition'         => ['NEW','USED'][array_rand(['NEW','USED'])],
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

            // If the ad is for rent, create RentDetail
            if ($adType === 'rent') {
                $this->createRentDetail($ad);
            }

            // Attach the list of marine images to this advertisement
            $this->createCategoryImages($ad, $this->marineImages($i));

            // Attach features for Marine
            $this->attachCategoryFeatures($ad);
        }

        // Create motorcycle Advertisements
        for ($i = 0; $i < 6; $i++) {
            $adType = $types[array_rand($types)]; // pick 'rent' or 'sale'
            // $ad_status = $adsStatuses[1];
            $ad_status = $adsStatuses[array_rand($adsStatuses)];
            if ($ad_status === 'rejected' || $ad_status === 'pending') {
                $active_status = 'inactive';
            } else {
                $active_status = 'active';
            }
            $ad = Advertisement::create([
                'title'        => $this->motorcycleTitles(),
                'description'  => $this->motorcycleDescriptions(),
                'city'         => $cities[array_rand($cities)],
                'price'        => rand(1000, 10000),
                'currency'     => 'USD',
                'location'     => $this->locations(),
                'category_id'  => CategoryType::MOTORCYCLE->value, // 5
                'user_id'      => rand(5, 50),
                'ads_status'   => $ad_status,
                'active_status'=> $active_status,
                'type'         => $adType,
                'image_upload_status' => 'completed',
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

            // If the ad is for rent, create RentDetail
            if ($adType === 'rent') {
                $this->createRentDetail($ad);
            }

            // Attach the list of marine images to this advertisement
            $this->createCategoryImages($ad, $this->motorcycleImages($i));

            // Attach features for Motorcycle
            $this->attachCategoryFeatures($ad);
        }
    }

    protected function landTitles()
    {
        $title = 'أرض مميزة بمساحة واسعة – فرصة لا تعوض!';

        return $title;
    }

    protected function landDescriptions()
    {
        $description = 'فرصة استثمارية رائعة! تتوفر قطعة أرض بمساحة واسعة في موقع مميز، مثالية لإنشاء مشروع تجاري أو سكني. تقع في منطقة حيوية وقريبة من جميع الخدمات الأساسية مثل المدارس، الأسواق، والمواصلات العامة. تتميز الأرض بطبيعة مستوية، مما يجعلها جاهزة للبناء فورًا.';
        return $description;
    }

    protected function locations()
    {
        $locations = [
            'الحمدانية',
            'العزيزية',
            'حلب الجديدة',
            'حي الأندلس',
            'حي الصاخور',
            'حي الفردوس',
            'حلب القديمة',
            'باب الحديد',
            'باب الفرج',
            'النيرب',
            'حي السريان الجديدة',
            'حي السريان القديمة',
            'حي صلاح الدين',
            'حي العقبة',
        ];

        return $locations[array_rand($locations)];
    }

     // Create SaleDetail for an ad that has type 'sale'.
     // Example: 'is_swap' can be random or forced. We do random here.
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

    // Randomly select from allowed values
    protected function createRentDetail(Advertisement $ad)
    {
        $periods = ['daily', 'weekly', 'monthly', 'yearly'];
        RentDetail::create([
            'advertisement_id' => $ad->id,
            'rental_period'    => $periods[array_rand($periods)],
        ]);
    }

    protected function landImages(int $i)
    {
        $landImages1 = [
            'https://cdn103.adwimg.com/w/241105/classifieds/0q3v0PAk02bjRI6EyblibjICpzTNWDPo.jpg',
            'https://cdn103.adwimg.com/w/241105/classifieds/FgNSZvTT0qOgSvaMrKs636lpeTRsAZpw.jpg',
        ];
        $landImages2 = [
            'https://cdn103.adwimg.com/w/241031/classifieds/06yXQ1IASMWO846OuWkoTYElTdrK6p3Q.jpg',
            'https://cdn103.adwimg.com/w/241031/classifieds/wut8MPunHhqZ363PQwDdyf5MwKEeEHVH.jpg',
            'https://cdn103.adwimg.com/w/241031/classifieds/yTuAgux6nvun1AhwxCJ60QuhueDGyJOK.jpg',
        ];
        $landImages3 = [
            'https://cdn103.adwimg.com/w/241210/classifieds/R9RwrUHOfw95cIe7DhY9zVVKpENWSgIY.jpg',
            'https://cdn103.adwimg.com/w/241210/classifieds/xCqTehYigRnt7UZvsOYVYXBhi2k2Gi05.jpg',
            'https://cdn103.adwimg.com/w/241210/classifieds/yglwNV1TJ9bPJc9q7Lec31dDqGJPXMb0.jpg',
        ];
        $landImages4 = [
            'https://cdn103.adwimg.com/w/250401/classifieds/2TS0g0tuGoTyozHawtwGv8V4aOwug4I5.png',
            'https://cdn103.adwimg.com/w/250401/classifieds/vOGRqE6oHsUNg9qPOQVF9QwwonzCATsY.png',
        ];
        $landImages5 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7497/1742980005_495bae59-ff44-4e3e-afc5-12d44c133c23.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7497/1742980005_daff8018-199e-45e5-b032-e11101838641.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7497/1742980005_27f08355-598f-4cc4-a72a-9458c6573c8f.png',
        ];
        $landImages6 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7461/1742980027_c78aa72c-a8b2-45ba-836a-dc5aef9fe59f.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7461/1742980027_e2455431-b5db-4dfb-a024-a73a2366f470.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7461/1742980027_fc13b83d-9d98-4619-b2ba-f99fd972606d.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7461/1742980027_38f0f83b-a24b-4baa-aefe-41d2a10bb133.png',
        ];

        $landImages = [
            $landImages1,
            $landImages2,
            $landImages3,
            $landImages4,
            $landImages5,
            $landImages6,
        ];
        return $landImages[$i];
    }

    protected function houseImages(int $i)
    {
        $houseImages1 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7494/1742980008_c721611b-407f-4ecf-9d79-a44f52d51aa9.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7494/1742980008_b36ed4c6-73bc-4d5f-8f94-ae40472b74d1.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7494/1742980008_e0b3ad6f-b36e-4083-98c4-dfc720b13bf7.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7494/1742980008_657828f8-36c9-4b3c-8c91-84ca8ca3c387.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7494/1742980008_0a129ea0-2afc-4e84-9eec-adee9a2d7d03.jpeg',
        ];
        $houseImages2 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7492/1742980005_299c43d9-dc44-43b0-a043-02481fc507d1.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7492/1742980005_258f7e89-d8b4-468d-9f37-df65ea63f9f4.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7492/1742980005_d7f66794-28af-47e8-b312-aa3a427d1530.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7492/1742980005_74774029-2f4a-4578-8b59-c32beda09339.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7492/1742980005_d5244af2-7d73-49c3-8358-8c5781d61430.jpeg',
        ];
        $houseImages3 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7474/1742980024_8c7ce22c-62c9-4a22-8d15-ed0fe9e235a8.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7474/1742980024_3e9a1994-f299-4ed0-ad0a-7604f87418e2.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7474/1742980024_c1879c7a-cf5b-432c-8352-9762d1b33142.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7474/1742980024_fe098d8d-5e4a-4f5a-a00c-a7c9afb305d1.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7474/1742980024_a390bc53-c1e8-4cdc-8ea8-8ad2b2066a8f.jpeg',
        ];
        $houseImages4 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7470/1742980025_d420ba4b-6f63-47e0-a194-ff2224b6c77e.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7470/1742980025_0ec614e1-652f-4ff3-8116-8fdcc5bb1000.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7470/1742980025_8de4cb2f-8096-46fd-baee-482bdbf9bbfc.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7470/1742980025_88d2f766-cda0-45d5-9a6d-8a0c3875e251.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7470/1742980025_b9df7b71-a12d-496c-a2c5-03e7a638be4f.jpeg',
        ];
        $houseImages5 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7445/1742980034_08df7683-70f4-407b-ad1c-c6ddabd64624.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7445/1742980034_56649d00-f357-4c5e-aba3-058cb11fdaae.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7445/1742980034_f17dd78e-2fe3-4243-b517-95661780b701.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7445/1742980034_19a58c09-55bb-4905-8141-2d6906d50786.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-S-7445/1742980034_cc65d2a0-6d36-4583-bb8c-cee94eb885bd.jpeg',
        ];
        $houseImages6 = [
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-R-3166/1742980006_b15c2013-7065-4cf9-a76f-b7fa92315c94.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-R-3166/1742980006_ebd7ccf7-b988-4dc3-9736-e8b8a3f6ec9f.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-R-3166/1742980006_aafc8523-49bf-42b3-b110-89a7e9f728df.jpeg',
            'https://www.homes-jordan.com/uploads/properties/orignal/HMM-R-3166/1742980006_029048f5-8237-4c23-86b6-59ccf1296efc.jpeg',
        ];

        $houseImages = [
            $houseImages1,
            $houseImages2,
            $houseImages3,
            $houseImages4,
            $houseImages5,
            $houseImages6,
        ];
        return $houseImages[$i];
    }

    protected function carImages(int $i)
    {
        $carImages1 = [
            'https://carandx.com/wp-content/uploads/2024/09/Toyota-Camry-2.5L-Hybrid-E-PLUS-HEV-2025-Black-1.jpg',
            'https://carandx.com/wp-content/uploads/2024/09/Toyota-Camry-2.5L-Hybrid-E-PLUS-HEV-2025-Black-2.jpg',
            'https://carandx.com/wp-content/uploads/2024/09/Toyota-Camry-2.5L-Hybrid-E-PLUS-HEV-2025-Black-3.jpg',
            'https://carandx.com/wp-content/uploads/2024/09/Toyota-Camry-2.5L-Hybrid-E-PLUS-HEV-2025-Black-21.jpg',
            'https://carandx.com/wp-content/uploads/2024/09/Toyota-Camry-2.5L-Hybrid-E-PLUS-HEV-2025-Black-14.jpg',
        ];

        $carImages2 = [
            'https://www.fordikinciel.com/B2ELResim/AracResim2El/114865/f157616c-678b-4de4-8d38-80c93ad6a43d_Buyuk.jpg',
            'https://www.fordikinciel.com/B2ELResim/AracResim2El/114865/bbfe2f25-284e-4bdf-ae6d-fbd29b40c5b8_Buyuk.jpg',
            'https://www.fordikinciel.com/B2ELResim/AracResim2El/114865/920df86f-f93d-4261-a587-e4ca6b35ce6c_Buyuk.jpg',
            'https://www.fordikinciel.com/B2ELResim/AracResim2El/114865/99bdf1f7-1900-439b-b74b-9c816b8d4d70_Buyuk.jpg',
            'https://www.fordikinciel.com/B2ELResim/AracResim2El/114865/f80da228-e2f7-41ac-ab49-4554e75d788d_Buyuk.jpg',
        ];
        $carImages3 = [
            'https://www.donanimhaber.com/cache-v2/?t=20230208151853&width=-1&text=0&path=https://www.donanimhaber.com/images/images/haber/160331/yeni-bmw-x5-ve-x6-tanitildi-iste-tasarimi-ve-ozellikleri160331_1.jpg',
            'https://www.donanimhaber.com/cache-v2/?t=20230208151853&width=-1&text=0&path=https://www.donanimhaber.com/images/images/haber/160331/yeni-bmw-x5-ve-x6-tanitildi-iste-tasarimi-ve-ozellikleri160331_2.jpg',
            'https://www.donanimhaber.com/cache-v2/?t=20230208151853&width=-1&text=0&path=https://www.donanimhaber.com/images/images/haber/160331/yeni-bmw-x5-ve-x6-tanitildi-iste-tasarimi-ve-ozellikleri160331_3.jpg',
            'https://www.donanimhaber.com/cache-v2/?t=20230208152729&width=-1&text=0&path=https://www.donanimhaber.com/images/images/galeri/7991/2024-bmw-x5-ve-x6-tanitildi-daha-guclu-motorlar-yeni-teknolojiler-7991-2.jpg',
            'https://www.donanimhaber.com/cache-v2/?t=20230208152729&width=-1&text=0&path=https://www.donanimhaber.com/images/images/galeri/7991/2024-bmw-x5-ve-x6-tanitildi-daha-guclu-motorlar-yeni-teknolojiler-7991-4.jpg',
        ];
        $carImages4 = [
            'https://media.ed.edmunds-media.com/bmw/3-series/2023/oem/2023_bmw_3-series_sedan_330i-xdrive_fq_oem_1_815.jpg',
            'https://media.ed.edmunds-media.com/bmw/3-series/2023/oem/2023_bmw_3-series_sedan_330i-xdrive_fq_oem_4_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/bmw/3-series/2023/oem/2023_bmw_3-series_sedan_330i-xdrive_fq_oem_7_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/bmw/3-series/2023/oem/2023_bmw_3-series_sedan_330i-xdrive_fq_oem_8_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/bmw/3-series/2023/oem/2023_bmw_3-series_sedan_m340i_fq_oem_1_1600x1067.jpg',
        ];
        $carImages5 = [
            'https://www.hyundai.com/content/dam/hyundai/in/en/data/find-a-car/Grand-i10-Nios/Gallery%20Section/big/pc/niosgallery_3.jpg',
            'https://www.hyundai.com/content/dam/hyundai/in/en/data/find-a-car/Grand-i10-Nios/Exterior/pc/Ext_512x340_1.jpg',
            'https://www.hyundai.com/content/dam/hyundai/in/en/data/find-a-car/Grand-i10-Nios/Highlights/Grandi10niosnew/high_1_512x340_2.jpg',
            'https://www.hyundai.com/content/dam/hyundai/in/en/data/find-a-car/Grand-i10-Nios/Highlights/Grandi10niosnew/Highlight_1120x600.jpg',
            'https://www.hyundai.com/content/dam/hyundai/in/en/data/find-a-car/Grand-i10-Nios/Highlights/Grandi10niosnew/336x180_2.jpg',
        ];
        $carImages6 = [
            'https://media.ed.edmunds-media.com/tesla/model-y/2026/oem/2026_tesla_model-y_4dr-suv_long-range-launch-series_fq_oem_1_815.jpg',
            'https://media.ed.edmunds-media.com/tesla/model-y/2026/oem/2026_tesla_model-y_4dr-suv_long-range-launch-series_fq_oem_2_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/tesla/model-y/2026/oem/2026_tesla_model-y_4dr-suv_long-range-launch-series_fq_oem_3_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/tesla/model-y/2026/oem/2026_tesla_model-y_4dr-suv_long-range-launch-series_fq_oem_5_1600x1067.jpg',
            'https://media.ed.edmunds-media.com/tesla/model-y/2026/oem/2026_tesla_model-y_4dr-suv_long-range-launch-series_rq_oem_2_1600x1067.jpg',
        ];

        $carImages = [
            $carImages1,
            $carImages2,
            $carImages3,
            $carImages4,
            $carImages5,
            $carImages6,
        ];
        return $carImages[$i];
    }

    protected function marineImages(int $i)
    {
        $marineImages1 = [
            'https://cdn.yachtbroker.org/images/highdef/2811813_ce7ed547_1.jpg',
            'https://cdn.yachtbroker.org/images/highdef/2811813_3b6bf107_2.jpg',
            'https://cdn.yachtbroker.org/images/highdef/2811813_ce95bedc_3.jpg',
            'https://cdn.yachtbroker.org/images/highdef/2811813_f44a661d_4.jpg',
            'https://cdn.yachtbroker.org/images/highdef/2811813_b10a1387_7.jpg',
        ];
        $marineImages2 = [
            'https://sp-ao.shortpixel.ai/client/to_avif,q_glossy,ret_img,w_850/https://tr.marmaristravel.net/wp-content/uploads/2016/11/Marmaris-Jet-Ski-6.jpg',
            'https://sp-ao.shortpixel.ai/client/to_avif,q_glossy,ret_img,w_850/https://tr.marmaristravel.net/wp-content/uploads/2016/11/Marmaris-Jet-Ski-5.jpg',
            'https://sp-ao.shortpixel.ai/client/to_avif,q_glossy,ret_img,w_850/https://tr.marmaristravel.net/wp-content/uploads/2016/11/Marmaris-Jet-Ski-7.jpg',
            'https://sp-ao.shortpixel.ai/client/to_avif,q_glossy,ret_img,w_850/https://tr.marmaristravel.net/wp-content/uploads/2016/11/Marmaris-Jet-Ski-8.jpg',
            'https://sp-ao.shortpixel.ai/client/to_avif,q_glossy,ret_img,w_850/https://tr.marmaristravel.net/wp-content/uploads/2016/11/Marmaris-Jet-Ski-1.jpg',
        ];
        $marineImages3 = [
            'https://silodrome.com/wp-content/uploads/2023/09/Allison-V12-Powered-Speed-Boat-1600x1066.jpg',
            'https://silodrome.com/wp-content/uploads/2023/09/Allison-V12-Powered-Speed-Boat-28-740x493.jpg',
            'https://silodrome.com/wp-content/uploads/2023/09/Allison-V12-Powered-Speed-Boat-24-740x493.jpg',
            'https://silodrome.com/wp-content/uploads/2023/09/Allison-V12-Powered-Speed-Boat-20-740x493.jpg',
            'https://silodrome.com/wp-content/uploads/2023/09/Allison-V12-Powered-Speed-Boat-12-740x494.jpg',
        ];
        $marineImages4 = [
            'https://www.windwardyachts.com/blog/wp-content/uploads/2024/02/azzam-luxury-yacht.jpg',
            'https://www.windwardyachts.com/blog/wp-content/uploads/2024/02/eclipse-yacht.jpg',
            'https://www.windwardyachts.com/blog/wp-content/uploads/2024/02/Oceanco-Jubilee.jpg',
            'https://www.windwardyachts.com/blog/wp-content/uploads/2024/02/Lady-Moura-luxury-yacht.jpg',
        ];
        $marineImages5 = [
            'https://images.boats.com/resize/wp/2/files/2024/08/lowe-931x666.jpg',
            'https://images.boats.com/resize/wp/2/files/2024/08/bass-cat-1000x555.jpg',
            'https://features.boats.com/boat-content/files/2015/09/grady-white-cuddy.jpg',
            'https://images.boats.com/resize/wp/2/files/2024/08/lund-1000x666.jpg',
            'https://features.boats.com/boat-content/files/2015/09/apex-angler-qwest-pontoon.jpg',
        ];
        $marineImages6 = [
            'https://www.yotspace.com/wp-content/uploads/YOTSPACE-superyacht-charters-AUR-3-scaled.jpg',
            'https://www.yotspace.com/wp-content/uploads/Charter-Yacht-Aurora-6.jpg',
            'https://www.yotspace.com/wp-content/uploads/YOTSPACE-superyacht-charters-AUR-5-scaled.jpg',
            'https://www.yotspace.com/wp-content/uploads/YOTSPACE-superyacht-charters-AUR-2-scaled.jpg',
            'https://www.yotspace.com/wp-content/uploads/YOTSPACE-superyacht-charters-AUR-8-scaled.jpg',
        ];

        $marineImages = [
            $marineImages1,
            $marineImages2,
            $marineImages3,
            $marineImages4,
            $marineImages5,
            $marineImages6,
        ];

        return $marineImages[$i];
    }

    protected function motorcycleImages(int $i)
    {
        $motorcyclesImages1 = [
            'https://cdn103.adwimg.com/w/250328/classifieds/Uzh0g1DfxFZxU7IwYk3AVhfLl0poqPnO.png',
            'https://cdn103.adwimg.com/w/250328/classifieds/MHi1IUTCr7TGoBSPzSIjKatZEWP6Funw.png',
            'https://cdn103.adwimg.com/w/250328/classifieds/A6YTB9jEVgZa94GjQMChmZ40UDxMxWGd.png',
            'https://cdn103.adwimg.com/w/250328/classifieds/3hJwZk742feToRt0bXd4fntZFPSZknxP.png',
        ];
        $motorcyclesImages2 = [
            'https://cdn103.adwimg.com/w/250326/classifieds/kCyOWwLMRQdXt2SViwpG6RkL1O2PGrWv.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/pXfPCNOyfqH6YQdn5c3jUpDk3D5fzBZn.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/GoRZGV9rN8dzGds37Nbm5qBNtcU9qvu8.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/jZQMZZH1h4GVO50vFqkQoCyWITVEN6ig.png',
        ];
        $motorcyclesImages3 = [
            'https://cdn103.adwimg.com/w/250214/classifieds/yJj6RJ4xWFyrwQB9zlswEMuaLDG4HFVQ.jpg',
            'https://cdn103.adwimg.com/w/250214/classifieds/CM85dA1drvrKRKsKBIx9KnAAMax1F8FY.jpg',
            'https://cdn103.adwimg.com/w/250214/classifieds/ELlNxDlldiZYo1TiBZZqeRMHiaPZ6E2r.jpg',
            'https://cdn103.adwimg.com/w/250214/classifieds/LywWrDt3965kc8eVME7Wr4TVLFwQCWZC.jpg',
            'https://cdn103.adwimg.com/w/250214/classifieds/KqDQtyc69XyGwReNg2Q5ZsFCMhhkZDFQ.jpg',
        ];
        $motorcyclesImages4 = [
            'https://cdn103.adwimg.com/w/250326/classifieds/j6HmDe53E5NrxVwSnchFy1XTM0CEcLUn.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/7PN0M4wGW6LdiVEB7iYpOh8vXn2QAs60.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/AiFMje66aUcRWmKGx36Kyt2PhnagTwXh.png',
            'https://cdn103.adwimg.com/w/250326/classifieds/a8WWGnpcwavB3fUxHqdPoQ6ikSYPjtmy.png',
        ];
        $motorcyclesImages5 = [
            'https://cdn103.adwimg.com/w/250322/classifieds/MQqtli7wfWl0bYAkTyqDOslSC2S6FEcE.jpg',
            'https://cdn103.adwimg.com/w/250322/classifieds/rxSUuz0BBj1FbUW64ncExtj0VY5Wqhoa.jpg',
            'https://cdn103.adwimg.com/w/250322/classifieds/1QFihqRhSc52gDlZeB5mFJL96ysjUy3c.jpg',
            'https://cdn103.adwimg.com/w/250322/classifieds/t8paA3E4y6WR6fcNPUNx4zTKBdfYbNsX.jpg',
            'https://cdn103.adwimg.com/w/250322/classifieds/QUp5X2TZsGo9W8xoekWb70tdvfB3PejX.jpg',
        ];
        $motorcyclesImages6 = [
            'https://cdn103.adwimg.com/w/241210/classifieds/kUbttfTA8HeQFCxRQz8Jv7JMZliQgjaF.png',
            'https://cdn103.adwimg.com/w/241210/classifieds/lGpmfeswZzMP00hqTcKCrJA1eTgcf31H.png',
            'https://cdn103.adwimg.com/w/241210/classifieds/q6DJ4cJ47VXJyaq9aLoVxBUVh6rdxQ64.png',
            'https://cdn103.adwimg.com/w/241210/classifieds/MwvEsil21kFhNYSbjZQq4YGy62091Jfm.png',
            'https://cdn103.adwimg.com/w/241210/classifieds/NFFfwuy1XqYmxQTtr3dicatFBZOkLbyq.png',
        ];

        $motorcylesImages = [
            $motorcyclesImages1,
            $motorcyclesImages2,
            $motorcyclesImages3,
            $motorcyclesImages4,
            $motorcyclesImages5,
            $motorcyclesImages6,
        ];
        return $motorcylesImages[$i];
    }

    // Create images for the given ad using the provided list of image paths.
    protected function createCategoryImages(Advertisement $ad, array $imagePaths)
    {
        foreach ($imagePaths as $path) {
            Image::create([
                'advs_id' => $ad->id, // Assuming 'advs_id' is the foreign key in your images table
                'url'     => $path,   // Assuming 'url' is the column storing the image path
            ]);
        }
    }

    // Attach all features that belong to the ad's category (no randomness).
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

    protected function houseTitles()
    {
        $titles = [
            "بيت للبيع – موقع مميز وسعر رائع!",
            "فيلا فاخرة للإيجار – تصميم عصري ومساحة واسعة",
            "منزل عائلي للإيجار – قريب من جميع الخدمات",
            "فرصة لا تفوت! بيت للبيع بسعر مناسب",
            "للبيع: بيت أنيق بموقع حيوي",
            "منزل راقي للإيجار – جاهز للسكن فورًا",
            "فيلا بموقع استراتيجي الإيجار – تشطيب عالي الجودة",
            "بيت للبيع – مساحة واسعة وتصميم رائع",
            "عش حياتك براحة! منزل فاخر للبيع",
            "فرصة استثمارية! بيت للإيجار بسعر مغر"
        ];

        return $titles[array_rand($titles)];
    }

    protected function houseDescriptions()
    {
        $properties = [
            "بيت رائع بموقع استراتيجي قريب من جميع الخدمات الأساسية مثل المدارس، الأسواق، والمستشفيات. يتميز بتصميم عصري ومساحة واسعة تناسب العائلات الكبيرة. يحتوي المنزل على غرف نوم مريحة، صالة فسيحة، مطبخ مجهز، وحديقة جميلة. جاهز للسكن فورًا!",
            "استمتع بالرفاهية في هذه الفيلا الرائعة التي تجمع بين التصميم العصري والمساحة الواسعة. تقع في منطقة هادئة وقريبة من المرافق الأساسية، وتتميز بحديقة خضراء واسعة، مسبح خاص، وغرف نوم ماستر مع حمامات فاخرة. مثالية للعائلات الباحثة عن الراحة والفخامة.",
            "بيت واسع ومجهز بالكامل بموقع حيوي، قريب من المواصلات والأسواق. يتميز بتصميم داخلي مريح، حديقة خاصة، وموقف سيارات واسع. مثالي للسكن العائلي أو للاستثمار."
        ];

        return $properties[array_rand($properties)];
    }

    protected function carTitles(int $i)
    {
        $titles = [
            'تويوتا كامري إي بلس',
            'تويوتا كورولا إكس إل آي 1.5 لتر',
            'بي ام دبليو اكس 5',
            'بي ام دبليو 3 سيريز',
            'هيونداي جراند i10 سمارت 2025',
            'تيسلا موديل Y 2021 بدفعة اولى و على الهوية الشخصية فقط',
        ];
        return $titles[$i];
    }

    protected function carDescriptions()
    {
        $descriptions = [
            "للبيع سيارة (أدخل نوع السيارة والموديل) بحالة ممتازة، خالية من الحوادث، ومجهزة بجميع الكماليات. محرك قوي، مكيف بارد، واستهلاك اقتصادي للوقود. جاهزة للاستخدام الفوري!",
            "للبيع سيارة (أدخل نوع السيارة والموديل) نظيفة من الداخل والخارج، مكينة وجير بحالة ممتازة، وتحتوي على جميع المواصفات الحديثة. السيارة بحالة الوكالة، وتم صيانتها بالكامل.",
            "إذا كنت تبحث عن سيارة عائلية مريحة واقتصادية، هذه السيارة هي الخيار الأمثل! متوفرة بمساحة داخلية واسعة، مقاعد جلدية، وتكييف مزدوج. السيارة بحالة ممتازة وجاهزة للسفر."
        ];

        return $descriptions[array_rand($descriptions)];
    }

    protected function marineTitles()
    {
        $titles = [
            "يخت أنيق للبيع أو للإيجار – رفاهية على الماء",
            "جيت سكي للبيع – مغامرة بحرية بانتظارك!",
            "زورق سريع للبيع – أداء قوي وتصميم مميز",
            "قارب فاخر للبيع – تجربة بحرية لا تُنسى!",
            "للبيع: مركب صيد مجهز بالكامل وجاهز للعمل",
            "يخت فاخر بمواصفات عالية للبيع – استمتع بالبحر بأسلوب راقٍ",
            "قارب عائلي للبيع – متعة الإبحار مع الأهل والأصدقاء",
            "جيت سكي بحالة ممتازة للبيع – استعد لمغامرة مثيرة!",
            "للبيع: قارب رياضي سريع – مثالي للرحلات البحرية",
            "مركب صيد للبيع – مجهز بأحدث الأدوات وبسعر مناسب"
        ];

        return $titles[array_rand($titles)];
    }

    protected function marineDescriptions()
    {
        $boats = [
            "استمتع بتجربة إبحار لا تُنسى مع هذا اليخت الفاخر المصمم بأحدث التقنيات. يتميز بمساحة واسعة، صالون فاخر، كبائن مريحة، ومطبخ مجهز بالكامل. مثالي للرحلات البحرية الطويلة أو المناسبات الخاصة.",
            "للبيع زورق سريع بمواصفات رياضية وتصميم حديث، مثالي لعشاق السرعة والإثارة في البحر. يتميز بمحرك قوي، نظام تحكم متطور، ومقاعد مريحة تناسب الرحلات القصيرة والاستكشافات البحرية.",
            "جيت سكي قوي وعالي الأداء بحالة ممتازة، مناسب لعشاق المغامرات البحرية والسرعة. يتميز بمحرك قوي، نظام تبريد متطور، وتصميم رياضي يوفر تجربة قيادة سلسة وآمنة.",
            "للبيع قارب صيد مجهز بجميع الأدوات اللازمة، مثالي للصيادين المحترفين والهواة. يتميز بمساحة تخزين واسعة، معدات ملاحة حديثة، ومقاعد مريحة لضمان تجربة صيد مثالية."
        ];

        return $boats[array_rand($boats)];
    }

    protected function motorcycleTitles()
    {
        $motorcycles = [
            "دراجة نارية رياضية للبيع – سرعة وأداء لا يُضاهى!",
            "دراجة نارية بحالة ممتازة للبيع – مغامرتك تبدأ هنا!",
            "للبيع: موتوسيكل سريع وأنيق – جاهز للانطلاق!",
            "دراجة نارية كلاسيكية للبيع – تجربة قيادة فريدة",
            "للبيع: سكوتر عملي واقتصادي – مثالي للمدينة",
            "موتوسيكل رياضي للبيع – لمحبي السرعة والإثارة",
            "دراجة نارية مميزة للبيع – تصميم رائع وأداء قوي",
            "للبيع: دراجة نارية بحالة الوكالة وبسعر مغرٍ!",
        ];

        return $motorcycles[array_rand($motorcycles)];
    }

    protected function motorcycleDescriptions()
    {
        $motorcycles = [
            "للبيع دراجة نارية رياضية بمواصفات عالية، مثالية لعشاق السرعة والإثارة. تتميز بمحرك قوي، هيكل خفيف الوزن، وتصميم رياضي يمنحك تجربة قيادة سلسة وممتعة. بحالة ممتازة وجاهزة للانطلاق!",
            "إذا كنت تبحث عن القوة والأناقة، فهذه الدراجة النارية من هارلي ديفيدسون هي الخيار المثالي! تصميم كلاسيكي فاخر، محرك قوي، ومقاعد مريحة تناسب الرحلات الطويلة.",
            "دراجة نارية عالية الأداء، مثالية للمسافات الطويلة والتنقل اليومي. تتميز بمحرك اقتصادي، استجابة سريعة، وتصميم مريح يضمن تجربة قيادة آمنة وممتعة.",
            "إذا كنت تبحث عن وسيلة نقل اقتصادية وسهلة الاستخدام، فهذا السكوتر هو الخيار الأمثل! يتميز بمحرك اقتصادي، تصميم خفيف، ومساحة تخزين عملية، مما يجعله مناسبًا للتنقل في المدينة بكل راحة."
        ];

        return $motorcycles[array_rand($motorcycles)];
    }
}
