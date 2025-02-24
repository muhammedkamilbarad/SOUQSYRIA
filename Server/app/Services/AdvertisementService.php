<?php
namespace App\Services;

use App\Repositories\AdvertisementRepository;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::denies('create', Advertisement::class)) {
            throw new \Exception('You do not have an active subscription or remaining ads.');
        }
        $advertisementData = $this->prepareAdvertisementData($data, $user);
        $specificData = $this->prepareSpecificData($data);

        $advertisement = $this->repository->createWithRelated(
            $advertisementData,
            $specificData,
            $data['category_id']
        );
        $this->decreaseRemainingAds($user);

        $relations = ['images'];
        if (in_array($data['category_id'], [3,4,5])) {
            $relations[] = 'vehicleAdvertisement';
        } if ($data['category_id'] == 3) {
            $relations[] = 'carAdvertisement';
        } elseif ($data['category_id'] == 5) {
            $relations[] = 'motorcycleAdvertisement';
        } elseif ($data['category_id'] == 4) {
            $relations[] = 'marineAdvertisement';
        } elseif ($data['category_id'] == 2) {
            $relations[] = 'houseAdvertisement';
        } elseif ($data['category_id'] == 1) {
            $relations[] = 'landAdvertisement';
        }
        return [
            'success' => true,
            'advertisement' => $advertisement->load($relations)
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

        if (in_array($data['category_id'], [3,4,5])) {
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

        switch ($data['category_id']) {
            case 3://'car':
                $specificData['car'] = [
                    'seats' => $data['seats'],
                    'doors' => $data['doors']
                ];
                break;
            case 5://'motorcycle':
                $specificData['motorcycle'] = [
                    'cylinders' => $data['cylinders']
                ];
                break;
            case 4://'marine':
                $specificData['marine'] = [
                    'type_id' => $data['marine_type_id'],
                    'length' => $data['length'],
                    'max_capacity' => $data['max_capacity']
                ];
                break;
            case 2://'house':
                $specificData['house'] = [
                    'number_of_rooms' => $data['number_of_rooms'],
                    'building_age' => $data['building_age'],
                    'square_meters' => $data['square_meters'],
                    'floor' => $data['floor']
                ];
                break;
            case 1://'land':
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

}
