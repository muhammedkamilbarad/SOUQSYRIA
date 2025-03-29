<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Filters\AdvertisementFilter;
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
        return $this->model->with($this->getCommonRelations())->where('user_id', $userId)->get();
    }

    public function getByIdWithRelations(int $id)
    {
        return $this->model->with($this->getCommonRelations())->find($id);
    }

    public function getBySlugWithRelations(string $slug)
    {
        return $this->model->with($this->getCommonRelations())->where('slug', $slug)->first();
    }

    public function getAllWithRelations(array $filters = [], int $perPage = 5)
    {
        $query = $this->model->with($this->getCommonRelations());
        return AdvertisementFilter::apply($query, $filters)->paginate($perPage);
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
}
