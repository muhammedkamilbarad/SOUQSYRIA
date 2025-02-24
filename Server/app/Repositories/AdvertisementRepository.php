<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
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

    public function createWithRelated(array $advertisementData, array $specificData, int $category_id)
    {
        \DB::beginTransaction();
        try{
            $advertisement = $this->create($advertisementData);
            switch($category_id){
                case 3://'car':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->carAdvertisement()->create($specificData['car']);
                    break;
                case 5://'motorcycle':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->motorcycleAdvertisement()->create($specificData['motorcycle']);
                    break;
                case 4://'marine':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->marineAdvertisement()->create($specificData['marine']);
                    break;
                case 2://'house':
                    $advertisement->houseAdvertisement()->create($specificData['house']);
                    break;
                case 1://'land':
                    $advertisement->landAdvertisement()->create($specificData['land']);
                    break;
            }
            if(isset($specificData['images'])){
                $this->createImages($advertisement, $specificData['images']);
            }
            \DB::commit();
            return $advertisement;
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
}
