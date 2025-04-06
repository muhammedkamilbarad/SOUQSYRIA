<?php
namespace App\Services;

use App\Repositories\AdvertisementRepository;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendRejectionMessageJob;
use App\Services\RecommendationStrategies\CarRecommendationStrategy;
use App\Services\RecommendationStrategies\HouseRecommendationStrategy;
use App\Services\RecommendationStrategies\LandRecommendationStrategy;
use App\Services\RecommendationStrategies\MarineRecommendationStrategy;
use App\Services\RecommendationStrategies\MotorcycleRecommendationStrategy;
use App\Services\RecommendationStrategies\RecommendationStrategyInterface;
use Illuminate\Database\Eloquent\Collection;

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

    public function getAllAdvertisements(array $filters = [], int $perPage = 5)
    {
        return $this->repository->getAllWithRelations($filters, $perPage);
    }
    public function getAllAdvertisementsForHomePage(array $filters = [], int $perPage = 5)
    {
        return $this->repository->getAllForHomePage($filters, $perPage);
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
            'currency' => $data['currency'],
            'city' => $data['city'],
            'location' => $data['location'],
            'category_id' => $data['category_id'],
            'user_id' => $user->id,
            'type' => $data['type'],
        ];
    }

    protected function prepareSpecificData(array $data)
    {
        $specificData = [];
        $category = CategoryType::tryFrom((int) $data['category_id']);
        if (in_array($category, [CategoryType::CAR, CategoryType::MOTORCYCLE, CategoryType::MARINE])) {
            $specificData['vehicle'] = [
                'color' => $data['color'],
                'mileage' => $data['mileage'],
                'year' => $data['year'],
                'engine_capacity' => $data['fuel_type'] === 'ELECTRIC' ? null : $data['engine_capacity'],
                'brand_id' => $data['brand_id'],
                'model_id' => $data['model_id'],
                'fuel_type' => $data['fuel_type'],
                'horsepower' => $data['horsepower'],
                'cylinders' => $data['fuel_type'] === 'ELECTRIC' ? null : $data['cylinders'],
                'transmission_type' => $data['transmission_type'],
                'condition' => $data['condition']
            ];
        }
        switch ($category) {
            case CategoryType::CAR:
                $specificData['car'] = [
                    'car_type' => $data['car_type'],
                    'seats' => $data['seats'],
                    'doors' => $data['doors'],
                    'seats_color' => $data['seats_color']
                ];
                break;
            case CategoryType::MOTORCYCLE:
                $specificData['motorcycle'] = [
                    'cooling_type' => $data['cooling_type'],
                    'motorcycle_type' => $data['motorcycle_type']
                ];
                break;
            case CategoryType::MARINE:
                $specificData['marine'] = [
                    'marine_type' => $data['marine_type'],
                    'length' => $data['length'] ?? null,
                    'max_capacity' => $data['max_capacity'] ?? null,
                ];
                break;
            case CategoryType::HOUSE:
                $specificData['house'] = [
                    'house_type' => $data['house_type'],
                    'number_of_rooms' => $data['number_of_rooms'],
                    'number_of_bathrooms' => $data['number_of_bathrooms'],
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
        if(isset($data['sale_details'])){
            $specificData['sale_details'] = $data['sale_details'];
        }
        if(isset($data['rent_details'])){
            $specificData['rent_details'] = $data['rent_details'];
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

        // Send rejection email if the status is rejected
        if ($data['status'] === 'rejected' && isset($data['message']))
        {
            // Ensure $data['message'] is a string; fallback to a default if it’s not
            $rejectionMessage = is_string($data['message']) ? $data['message'] : 'No specific reason provided.';
            Log::info('Rejection message is: ' . $rejectionMessage . '....' . $advertisement->user->email);
            SendRejectionMessageJob::dispatch(
                $advertisement->user->email,
                $advertisement->user->name,
                $rejectionMessage
            );
        }
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
        $allowed_fileds = ['title', 'description', 'price', 'currency', 'city', 'location'];
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
        if (isset($data['images'])) {
            $specificData['images'] = $data['images'];
        }
        if (isset($data['features'])) {
            $specificData['features'] = $data['features'];
        }
        if (isset($data['sale_details'])) {
            $specificData['sale_details'] = $data['sale_details'];
        }
        if (isset($data['rent_details'])) {
            $specificData['rent_details'] = $data['rent_details'];
        }
        return $specificData;
    }

    // Get similar advertisements based on the given advertisement ID
    public function getSimilarAdvertisements(int $advertisementId, int $limit = 5): Collection
    {
        Log::info("Starting getSimilarAdvertisements with ID: $advertisementId and limit: $limit");
        
        // Load advertisement with relationships
        $advertisement = $this->repository->getByIdWithRelations($advertisementId);
        $categoryId = $advertisement->category_id;
        
        // Get base query for similar advertisements
        $query = $this->repository->getSimilarAdvertisementsBaseQuery($advertisementId, $categoryId);
        
        // Get the appropriate strategy for this category
        $strategy = $this->getRecommendationStrategy($categoryId);
        
        // Add necessary relations to the query based on the strategy
        $query = $strategy->addRelationsToQuery($query);
        
        // Use the strategy to get similar advertisements
        return $strategy->getSimilarAdvertisements($advertisement, $query, $limit);
    }
    
    // Factory method to get the appropriate recommendation strategy
    private function getRecommendationStrategy(int $categoryId): RecommendationStrategyInterface
    {
        switch ($categoryId) {
            case CategoryType::LAND->value:
                return new LandRecommendationStrategy($this->repository);
            case CategoryType::HOUSE->value:
                return new HouseRecommendationStrategy($this->repository);
            case CategoryType::CAR->value:
                return new CarRecommendationStrategy($this->repository);
            case CategoryType::MARINE->value:
                return new MarineRecommendationStrategy($this->repository);
            case CategoryType::MOTORCYCLE->value:
                return new MotorcycleRecommendationStrategy($this->repository);
            default:
                throw new \InvalidArgumentException("No strategy found for category ID: $categoryId");
        }
    }
}



