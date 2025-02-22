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


    public function getByUserId(int $userId)
    {
        return $this->model->with([
            'user',
            'city',
            'category',
            'images',
            'vehicleAdvertisement',
            'carAdvertisement',
            'motorcycleAdvertisement',
            'marineAdvertisement',
            'houseAdvertisement',
            'landAdvertisement',
        ])->where('user_id', $userId)->get();
    }

    /*
     * Retrieves all advertisements with related models.
     *
     */
    public function getAllWithRelations()
{
    return $this->model->with([
        'user',
        'city',
        'category',
        'images',
        // Load these as direct relationships to Advertisement
        'vehicleAdvertisement',
        'carAdvertisement',
        'motorcycleAdvertisement',
        'marineAdvertisement',
        'houseAdvertisement',
        'landAdvertisement',
    ])->get();
}

    public function createWithRelated(array $advertisementData, array $specificData, string $category)
    {
        \DB::beginTransaction();
        try{
            $advertisement = $this->create($advertisementData);
            switch($category){
                case 'car':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->carAdvertisement()->create($specificData['car']);
                    break;
                case 'motorcycle':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->motorcycleAdvertisement()->create($specificData['motorcycle']);
                    break;
                case 'marine':
                    $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
                    $advertisement->marineAdvertisement()->create($specificData['marine']);
                    break;
                case 'house':
                    $advertisement->houseAdvertisement()->create($specificData['house']);
                    break;
                case 'land':
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
