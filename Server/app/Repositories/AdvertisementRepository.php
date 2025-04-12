<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\AdvertisementFilter;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Enums\CategoryType;
use App\Repositories\Advertisements\AdvertisementRepositoryFactory;
use Exception;
use App\Services\AdvertisementImageService;
use App\Filters\HomePageAdvertisementFilter;
use App\Services\ImageUploadService;


class AdvertisementRepository extends BaseRepository
{
    protected $imageUploadService;
    public function __construct(Advertisement $model, ImageUploadService $imageUploadService)
    {
        parent::__construct($model);
        $this->imageUploadService = $imageUploadService;
    }

    public function getCommonRelations(): array
    {
        return [
            'saleDetail',
            'rentDetail',
            'user',
            'category',
            'images',
            'vehicleAdvertisement' => function($query){
                $query->with('vehicleBrand', 'vehicleModel');
            },
            'carAdvertisement',
            'motorcycleAdvertisement',
            'marineAdvertisement',
            'houseAdvertisement',
            'landAdvertisement',
            'features' => function($query){
                $query->with('featureGroup');
            },
        ];
    }


    public function getByUserId(int $userId)
    {
        return $this->model->with(['user','category'])->where('user_id', $userId)->get();
    }

    public function getByIdWithRelations(int $id)
    {
        return $this->model->with($this->getCommonRelations())->find($id);
    }

    public function getAllWithRelations(array $filters = [], int $perPage = 5)
    {
        $query = $this->model->with(['user','category']);
        return AdvertisementFilter::apply($query, $filters)->paginate($perPage);
    }

    public function getAllForHomePage(array $filters = [], int $perPage = 5)
    {
        $query = $this->model->with($this->getCommonRelations());
        $query->where('active_status', 'active');
        $query->where('ads_status', 'accepted');
        return HomePageAdvertisementFilter::apply($query, $filters)->paginate($perPage);
    }

    public function createWithRelated(array $advertisementData, array $specificData)
    {
        $relations = [];
        \DB::beginTransaction();
        try{
            $advertisement = $this->create($advertisementData);
            $category = CategoryType::tryFrom($advertisementData['category_id']);
            $repository = AdvertisementRepositoryFactory::create($category);
            $repository->createSpecific($advertisement, $specificData);
            if(isset($specificData['images'])){
                $this->createImages($advertisement, $specificData['images']);
                $relations = ['images'];
            }
            if(isset($specificData['features'])){
                $advertisement->features()->sync($specificData['features']);
                $relations = array_merge($relations, ['features']);
            }
            if(isset($specificData['sale_details'])){
                $advertisement->saleDetail()->create($specificData['sale_details']);
                $relations = array_merge($relations, ['saleDetail']);
            }
            if(isset($specificData['rent_details'])){
                $advertisement->rentDetail()->create($specificData['rent_details']);
                $relations = array_merge($relations, ['rentDetail']);
            }
            \DB::commit();
            $relations = array_merge($relations, $repository->getRelations());
            return $advertisement->load($relations);
        } catch(\Exception $e){
            \DB::rollBack();
            throw $e;
        }
    }

    protected function createImages(Advertisement $advertisement, array $images)
    {
        $uploadedImages = $this->imageUploadService->uploadAdvertisementImages($advertisement->id, $images);
        $advertisement->images()->createMany($uploadedImages);
    }


    // protected function createImages(Advertisement $advertisement, array $images)
    // {
    //     $imageService = app(AdvertisementImageService::class);
    //     $batch = $imageService->uploadImageBatch($advertisement, $images);
    // }


    public function delete(Model $advertisement)
    {
        \DB::beginTransaction();
        try{
            foreach($advertisement->images as $image)
            {
                \Storage::disk('public')->delete($image->url);
            }
            $result = $advertisement->delete();
            \DB::commit();
            return $result;
        } catch(\Exception $e){
            \DB::rollBack();
            throw $e;
        }
    }

    public function updateWithRelated(Advertisement $advertisement, array $advertisementData, array $specificData)
    {
        $relations = [];
        \DB::beginTransaction();
        try{
            $advertisement->update($advertisementData);
            $category = CategoryType::tryFrom((int) $advertisement->category_id);
            $repository = AdvertisementRepositoryFactory::create($category);
            if (isset($specificData['images']) && !empty($specificData['images'])) {
                $this->updateImages($advertisement, $specificData['images']);
                $relations[] = 'images';
            }
            if (isset($specificData['features'])) {
                $advertisement->features()->sync($specificData['features']);
                $relations[] = 'features';
            }
            if (isset($specificData['sale_details'])) {
                $advertisement->saleDetail()->update($specificData['sale_details']);
                $relations[] = 'saleDetail';
            }
            if(isset($specificData['rent_details'])){
                $advertisement->rentDetail()->update($specificData['rent_details']);
                $relations[] = 'rentDetail';
            }
            DB::commit();
            $relations = array_merge($relations, $repository->getRelations());
            return $advertisement->load($relations);
        } catch (Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    protected function updateImages(Advertisement $advertisement, array $images)
    {
        foreach ($advertisement->images as $image) {
            \Storage::disk('public')->delete($image->url);
        }
        $advertisement->images()->delete();
        $this->createImages($advertisement, $images);
    }

    // Get base query for similar advertisements
    public function getSimilarAdvertisementsBaseQuery(int $advertisementId, int $categoryId): Builder
    {
        return $this->model->where('category_id', $categoryId)
            ->where('id', '!=', $advertisementId)
            ->where('active_status', true);
    }

    // Get candidates from the same city
    public function getCandidatesFromSameCity(Builder $query, string $city): Collection
    {
        $sameCityQuery = clone $query;
        return $sameCityQuery->where('city', $city)->get();
    }

    // Get candidates from different cities
    public function getCandidatesFromOtherCities(Builder $query, string $city): Collection
    {
        $otherCityQuery = clone $query;
        return $otherCityQuery->where('city', '!=', $city)->get();
    }
}
