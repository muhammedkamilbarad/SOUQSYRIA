<?php
namespace App\Services;

use App\Repositories\AdvertisementRepository;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\ModelNotFoundException;




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
        $category = CategoryType::tryFrom((int) $data['category_id']);
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
        if(isset($data['features'])){
            $specificData['features'] = $data['features'];
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
        try {
            $advertisement = $this->repository->getById($advId);
        } catch (ModelNotFoundException $e) {
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
        if (Gate::denies('update', $advertisement)) {
            throw new \Exception('You are not authorized to update this advertisement');
        }
        $advertisementData = $this->prepareAdvertisementUpdateData($data);
        $advertisementData['ads_status'] = 'pending';
        $advertisementData['active_status'] = 'inactive';
        $specificData = $this->prepareSpecificUpdateData($data, $advertisement->category_id);
        $updated = $this->repository->updateWithRelated(
            $advertisement,
            $advertisementData,
            $specificData
        );

        return [
            'success' => true,
            'message' => 'Advertisement updated successfully. It is now pending review.',
            'advertisement' => $updated
        ];
    }

    protected function prepareAdvertisementUpdateData(array $data)
    {
        $updateData = [];
        $allowed_fileds = ['title', 'description', 'price', 'location', 'city_id', 'type'];
        foreach($allowed_fileds as $filed)
        {
            if(isset($data[$filed]))
            {
                $updateData[$filed] = $data[$filed];
            }
        }
        return $updateData;
    }

    protected function prepareSpecificUpdateData(array $data, int $categoryId)
    {
        $specificData = [];
        $category = CategoryType::tryFrom((int) $categoryId);
        if (in_array($category, [CategoryType::CAR, CategoryType::MOTORCYCLE, CategoryType::MARINE]))
        {
            $vehicleFields = [
                'color_id', 'mileage', 'year', 'engine_capacity',
                'fuel_type_id', 'horsepower', 'transmission_id', 'condition'
            ];
            $vehicleData = $this->extractDataForFields($data, $vehicleFields);
            if (!empty($vehicleData)) {
                $specificData['vehicle'] = $vehicleData;
            }
        }
        switch ($category)
        {
            case CategoryType::CAR:
                $specificData['car'] = $this->extractDataForFields($data, ['seats', 'doors']);
                break;
            case CategoryType::MOTORCYCLE:
                $specificData['motorcycle'] = $this->extractDataForFields($data, ['cylinders']);
                break;
            case CategoryType::MARINE:
                $specificData['marine'] = $this->extractDataForFields($data,['marine_type_id', 'length', 'max_capacity']);
                break;
            case CategoryType::HOUSE:
                $specificData['house'] = $this->extractDataForFields($data,['number_of_rooms', 'building_age', 'square_meters', 'floor']);
                break;
            case CategoryType::LAND:
                $specificData['land'] = $this->extractDataForFields($data, ['square_meters']);
                break;
        }
        if (isset($data['images'])) {
            $specificData['images'] = $data['images'];
        }
        if (isset($data['features'])) {
            $specificData['features'] = $data['features'];
        }
        return $specificData;
    }

    protected function extractDataForFields(array $data, array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $field) {
            $dataField = is_string($key) ? $key : $field;
            $resultField = is_string($key) ? $field : $field;
            if (isset($data[$dataField])) {
                $result[$resultField] = $data[$dataField];
            }
        }
        return $result;
    }
}



