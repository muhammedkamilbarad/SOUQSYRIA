<?php
namespace App\Services;

use App\Repositories\AdvertisementRepository;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use App\Enums\CategoryType;



class AdvertisementService
{
    protected $repository;

    public function __construct(AdvertisementRepository $repository)
    {
        $this->repository = $repository;
    }


    public function getAdvertisementsByUser(User $user)
    {
        return $this->repository->getByUserId($user->id);
    }

    public function getAdvertisementById(int $id)
    {
        return $this->repository->getByIdWithRelations($id);
    }

    public function getAllAdvertisements(array $filters = [])
    {
        return $this->repository->getAllWithRelations($filters);
    }

    public function create(array $data, User $user)
    {
        // if (Gate::denies('create', Advertisement::class)) {
        //     throw new \Exception('You do not have an active subscription or remaining ads.');
        // }
        $advertisementData = $this->prepareAdvertisementData($data, $user);
        $specificData = $this->prepareSpecificData($data);
        $advertisement = $this->repository->createWithRelated(
            $advertisementData,
            $specificData,
        );
        //$this->decreaseRemainingAds($user);
        return [
            'success' => true,
            'advertisement' => $advertisement
        ];
    }

    protected function prepareAdvertisementData(array $data, User $user)
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'city_id' => $data['city_id'],
            'location' => $data['location'],
            'category_id' => $data['category_id'],
            'user_id' => $user->id,
            'type' => $data['type']
        ];
    }

    protected function prepareSpecificData(array $data)
    {
        $specificData = [];
        $category = CategoryType::tryFrom((int)$data['category_id']);
        if (in_array($category, [CategoryType::CAR, CategoryType::MOTORCYCLE, CategoryType::MARINE])) {
            $specificData['vehicle'] = [
                'color_id' => $data['color_id'],
                'mileage' => $data['mileage'],
                'year' => $data['year'],
                'engine_capacity' => $data['engine_capacity'],
                'brand_id' => $data['brand_id'],
                'model_id' => $data['model_id'],
                'fuel_type_id' => $data['fuel_type_id'],
                'horsepower' => $data['horsepower'],
                'transmission_id' => $data['transmission_id'],
                'condition' => $data['condition']
            ];
        }
        switch ($category) {
            case CategoryType::CAR:
                $specificData['car'] = [
                    'seats' => $data['seats'],
                    'doors' => $data['doors']
                ];
                break;
            case CategoryType::MOTORCYCLE:
                $specificData['motorcycle'] = [
                    'cylinders' => $data['cylinders']
                ];
                break;
            case CategoryType::MARINE:
                $specificData['marine'] = [
                    'type_id' => $data['marine_type_id'],
                    'length' => $data['length'],
                    'max_capacity' => $data['max_capacity']
                ];
                break;
            case CategoryType::HOUSE:
                $specificData['house'] = [
                    'number_of_rooms' => $data['number_of_rooms'],
                    'building_age' => $data['building_age'],
                    'square_meters' => $data['square_meters'],
                    'floor' => $data['floor']
                ];
                break;
            case CategoryType::LAND:
                $specificData['land'] = [
                    'square_meters' => $data['square_meters']
                ];
                break;
        }
        if (isset($data['images'])) {
            $specificData['images'] = $data['images'];
        }
        return $specificData;
    }

    protected function decreaseRemainingAds(User $user)
    {
        $subscription = $user->subscribings()->where('expiry_date', '>', now())
        ->where('remaining_ads', '>', 0)->first();
        if (!$subscription) {
            throw new \Exception('You do not have an active subscription or remaining ads.');
        }
        $subscription->decrement('remaining_ads');
    }

    public function processAdvertisement(int $advId, array $data)
    {
        $advertisement = $this->repository->getById($advId);
        if (!$advertisement) {
            throw new \Exception('Advertisement not found');
        }
        $updateData = [
            'ads_status' => $data['status'],
            'active_status' => ($data['status'] === 'accepted') ? 'active' : 'inactive'
        ];
        return $this->repository->update($advertisement, $updateData);
    }

    public function deleteAdvertisement(int $advId)
    {
        $advertisement = $this->repository->getByIdWithRelations($advId);
        if (!$advertisement) {
            throw new \Exception('Advertisement not found');
        }
        if(Gate::denies('delete', $advertisement)){
            throw new \Exception('You are not authorized to delete this advertisement');
        }
        return [
            'success' => $this->repository->delete($advertisement),
            'message' => 'Advertisement deleted successfully'
        ];
    }

    public function updateAdvertisement(int $id, array $data, User $user)
    {
        $advertisement = $this->repository->getByIdWithRelations($id);

        if (!$advertisement) {
            throw new \Exception('Advertisement not found');
        }

        // Check if user is authorized to update this advertisement
        if (Gate::denies('update', $advertisement)) {
            throw new \Exception('You are not authorized to update this advertisement');
        }

        // Prepare advertisement main data
        $advertisementData = $this->prepareAdvertisementUpdateData($data);

        // Always set status to pending after update
        $advertisementData['ads_status'] = 'pending';
        $advertisementData['active_status'] = 'inactive';

        // Prepare specific data
        $specificData = $this->prepareSpecificUpdateData($data, $advertisement->category_id);

        // Update the advertisement
        $updated = $this->repository->updateWithRelated(
            $advertisement,
            $advertisementData,
            $specificData
        );

        // Get the updated advertisement with all its relations
        $relations = ['images'];
        if (in_array($advertisement->category_id, [3,4,5])) {
            $relations[] = 'vehicleAdvertisement';
        }
        if ($advertisement->category_id == 3) {
            $relations[] = 'carAdvertisement';
        } elseif ($advertisement->category_id == 5) {
            $relations[] = 'motorcycleAdvertisement';
        } elseif ($advertisement->category_id == 4) {
            $relations[] = 'marineAdvertisement';
        } elseif ($advertisement->category_id == 2) {
            $relations[] = 'houseAdvertisement';
        } elseif ($advertisement->category_id == 1) {
            $relations[] = 'landAdvertisement';
        }

        return [
            'success' => true,
            'message' => 'Advertisement updated successfully. It is now pending review.',
            'advertisement' => $updated->load($relations)
        ];
    }

    protected function prepareAdvertisementUpdateData(array $data)
    {
        $updateData = [];

        // Only include fields that are actually submitted
        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['price'])) {
            $updateData['price'] = $data['price'];
        }
        if (isset($data['location'])) {
            $updateData['location'] = $data['location'];
        }
        if (isset($data['type'])) {
            $updateData['type'] = $data['type'];
        }

        return $updateData;
    }

    protected function prepareSpecificUpdateData(array $data, int $categoryId)
    {
        $specificData = [];

        if (in_array($categoryId, [3,4,5])) {
            $vehicleData = [];

            // Check each vehicle field and add if present
            if (isset($data['color_id'])) {
                $vehicleData['color_id'] = $data['color_id'];
            }
            if (isset($data['mileage'])) {
                $vehicleData['mileage'] = $data['mileage'];
            }
            if (isset($data['year'])) {
                $vehicleData['year'] = $data['year'];
            }
            if (isset($data['engine_capacity'])) {
                $vehicleData['engine_capacity'] = $data['engine_capacity'];
            }
            if (isset($data['brand_id'])) {
                $vehicleData['brand_id'] = $data['brand_id'];
            }
            if (isset($data['model_id'])) {
                $vehicleData['model_id'] = $data['model_id'];
            }
            if (isset($data['fuel_type_id'])) {
                $vehicleData['fuel_type_id'] = $data['fuel_type_id'];
            }
            if (isset($data['horsepower'])) {
                $vehicleData['horsepower'] = $data['horsepower'];
            }
            if (isset($data['transmission_id'])) {
                $vehicleData['transmission_id'] = $data['transmission_id'];
            }
            if (isset($data['condition'])) {
                $vehicleData['condition'] = $data['condition'];
            }

            if (!empty($vehicleData)) {
                $specificData['vehicle'] = $vehicleData;
            }
        }

        switch ($categoryId) {
            case 3: // car
                $carData = [];
                if (isset($data['seats'])) {
                    $carData['seats'] = $data['seats'];
                }
                if (isset($data['doors'])) {
                    $carData['doors'] = $data['doors'];
                }

                if (!empty($carData)) {
                    $specificData['car'] = $carData;
                }
                break;

            case 5: // motorcycle
                $motorcycleData = [];
                if (isset($data['cylinders'])) {
                    $motorcycleData['cylinders'] = $data['cylinders'];
                }

                if (!empty($motorcycleData)) {
                    $specificData['motorcycle'] = $motorcycleData;
                }
                break;

            case 4: // marine
                $marineData = [];
                if (isset($data['marine_type_id'])) {
                    $marineData['type_id'] = $data['marine_type_id'];
                }
                if (isset($data['length'])) {
                    $marineData['length'] = $data['length'];
                }
                if (isset($data['max_capacity'])) {
                    $marineData['max_capacity'] = $data['max_capacity'];
                }

                if (!empty($marineData)) {
                    $specificData['marine'] = $marineData;
                }
                break;

            case 2: // house
                $houseData = [];
                if (isset($data['number_of_rooms'])) {
                    $houseData['number_of_rooms'] = $data['number_of_rooms'];
                }
                if (isset($data['building_age'])) {
                    $houseData['building_age'] = $data['building_age'];
                }
                if (isset($data['square_meters'])) {
                    $houseData['square_meters'] = $data['square_meters'];
                }
                if (isset($data['floor'])) {
                    $houseData['floor'] = $data['floor'];
                }

                if (!empty($houseData)) {
                    $specificData['house'] = $houseData;
                }
                break;

            case 1: // land
                $landData = [];
                if (isset($data['square_meters'])) {
                    $landData['square_meters'] = $data['square_meters'];
                }

                if (!empty($landData)) {
                    $specificData['land'] = $landData;
                }
                break;
        }

        if (isset($data['images'])) {
            $specificData['images'] = $data['images'];
        }

        return $specificData;
    }




}
