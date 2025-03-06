<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Enums\CategoryType;
use App\Repositories\Advertisements\AdvertisementRepositoryFactory;
use Exception;


class AdvertisementRepository extends BaseRepository
{
    public function __construct(Advertisement $model)
    {
        parent::__construct($model);
    }

    private function getCommonRelations(): array
    {
        return [
            'user',
            'city',
            'category',
            'images',
            'vehicleAdvertisement' => function($query){
                $query->with('color', 'vehicleBrand', 'vehicleModel', 'fuelType', 'transmissionType');
            },
            'carAdvertisement',
            'motorcycleAdvertisement',
            'marineAdvertisement' => function($query){
                $query->with('marineType');
            },
            'houseAdvertisement',
            'landAdvertisement',
            'features' => function($query){
                $query->with('featureGroup');
            },
        ];
    }

    public function getByUserId(int $userId)
    {
        return $this->model->with($this->getCommonRelations())->where('user_id', $userId)->get();
    }

    public function getByIdWithRelations(int $id)
    {
        return $this->model->with($this->getCommonRelations())->find($id);
    }

    public function getAllWithRelations(array $filters = [])
    {
        $query = $this->model->with($this->getCommonRelations());
        $query = $this->applyFilters($query, $filters)->get();
        return $query;
    }

    private function applyFilters($query, array $filters)
    {
        if(isset($filters['ads_status']))
        {
            $query->where('ads_status', $filters['ads_status']);
        }
        if(isset($filters['active_status']))
        {
            $query->where('active_status', $filters['active_status']);
        }
        return $query;
    }

    public function createWithRelated(array $advertisementData, array $specificData)
    {
        \DB::beginTransaction();
        try{
            $advertisement = $this->create($advertisementData);
            $category = CategoryType::tryFrom($advertisementData['category_id']);
            $repository = AdvertisementRepositoryFactory::create($category);
            $repository->createSpecific($advertisement, $specificData);
            if(isset($specificData['images'])){
                $this->createImages($advertisement, $specificData['images']);
            }
            if(isset($specificData['features'])){
                $advertisement->features()->sync($specificData['features']);
            }
            \DB::commit();
            $relations = array_merge(['images','features'], $repository->getRelations());
            return $advertisement->load($relations);
        } catch(\Exception $e){
            \DB::rollBack();
            throw $e;
        }
    }

    protected function createImages(Advertisement $advertisement, array $images): void
    {
        $imagesData = [];
        foreach($images as $image){
            $imagesData[] = [
                'url' => $image->store('images', 'public'),
                'advs_id' => $advertisement->id,
            ];
        }
        $advertisement->images()->createMany($imagesData);
    }


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

    public function updateWithRelated(Advertisement $advertisement, array $mainData, array $specificData)
    {
        \DB::beginTransaction();
        try{
            \Log::info('Main Data:', $mainData);
            \Log::info('Specific Data:', $specificData);

            $advertisement->update($mainData);
            switch($advertisement->category_id)
            {
                case 3: // car
                    if (isset($specificData['vehicle'])) {
                        $advertisement->vehicleAdvertisement()->update($specificData['vehicle']);
                    }
                    if (isset($specificData['car'])) {
                        $advertisement->carAdvertisement()->update($specificData['car']);
                    }
                    break;
                case 5: // motorcycle
                    if (isset($specificData['vehicle'])) {
                        $advertisement->vehicleAdvertisement()->update($specificData['vehicle']);
                    }
                    if (isset($specificData['motorcycle'])) {
                        $advertisement->motorcycleAdvertisement()->update($specificData['motorcycle']);
                    }
                    break;
                case 4: // marine
                    if (isset($specificData['vehicle'])) {
                        $advertisement->vehicleAdvertisement()->update($specificData['vehicle']);
                    }
                    if (isset($specificData['marine'])) {
                        $advertisement->marineAdvertisement()->update($specificData['marine']);
                    }
                    break;
                case 2: // house
                    if (isset($specificData['house'])) {
                        $advertisement->houseAdvertisement()->update($specificData['house']);
                    }
                    break;
                case 1: // land
                    if (isset($specificData['land'])) {
                        $advertisement->landAdvertisement()->update($specificData['land']);
                    }
                    break;
            }
            if (isset($specificData['images']) && !empty($specificData['images'])) {
                $this->updateImages($advertisement, $specificData['images']);
            }
            DB::commit();
            return $advertisement->fresh();

        } catch (Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function updateImages(Advertisement $advertisement, array $images)
    {
        foreach ($advertisement->images as $image) {
            \Storage::disk('public')->delete($image->url);
        }
        $advertisement->images()->delete();
        $this->createImages($advertisement, $images);
    }
}
